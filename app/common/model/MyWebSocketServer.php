<?php

namespace app\common\model;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use React\EventLoop\LoopInterface;
use think\cache\driver\Redis;

// 重新加载配置文件
// 创建WebSocket服务器类
class MyWebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected object $redis;

    public function __construct(LoopInterface $loop)
    {
        $this->redis = new Redis();
//        $this->redis->flushAll(true);
        $this->clients = new \SplObjectStorage;
        $loop->addPeriodicTimer(4, function () {
            foreach ($this->clients as $client) {
                $client->send($this->getMessage($client));
            }
        });
    }

    public function getMessage($client)
    {

        $allClients = $this->getClients();
        $clearAllCats = false;
        if ($this->redis->keys('clear')) {
            $clearAllCats = true;
        }
        $currentClient = $client->httpRequest->getUri()->getQuery();
        $carKeys = $this->redis->keys('car_*');
        $cars = [];
        $httpData = [];
        print_r("this->redis->get('clear')");
        print_r($this->redis->get('clear'));
        if (!empty($carKeys)) {
            foreach ($carKeys as $key) {
                $cars[] = json_decode($this->redis->get($key), 1);
                foreach ($allClients as $thatClient) {
                    if ($this->redis->get('clear')) {
                        echo 'clear,clearclear,clearclear,clearclear,clearclear,clearclear,clear';
                        $this->redis->set($thatClient . 'clear', 1);
                    }
                    $this->redis->set($thatClient . '_' . $key, json_decode($this->redis->get($key), 1));
                }
                $this->redis->del($key);
            }
            foreach ($cars as $car) {

                $httpData[] = [
                    'plate' => $car['plate'],
                    'entry_id' => $car['entry_id'],
                    'status' => $car['status'],
                    'id' => $car['id'],
                ];
            }
            $responseData = $this->post_request('http://localhost/wechat/Parking/update', json_encode($httpData));
        }
        //获取现在的用户

        foreach ($allClients as $thatClient) {
            if ($this->redis->get('clear')) {
                echo 'clear,clearclear,clearclear,clearclear,clearclear,clearclear,clear';
                $this->redis->set($thatClient . 'clear', 1);
            }
        }
        $clientCarKeys = $this->redis->keys($currentClient . '_*');


        $cars = [];
        file_put_contents('access_log', '所有key:' . PHP_EOL, FILE_APPEND);
        file_put_contents('access_log', json_encode($this->redis->keys('*')) . PHP_EOL, FILE_APPEND);
        foreach ($clientCarKeys as $key) {
            $cars[] = $this->redis->get($key);
            $this->redis->del($key);
        }
        if ($this->redis->get($currentClient . 'clear')) {
            $this->redis->set('clear', false);
            $this->redis->set($currentClient . 'clear', 0);
            file_put_contents('access_log', '清除:' . PHP_EOL, FILE_APPEND);

            return json_encode(['type' => 'clear', 'data' => []]);
        } else {
            if(!empty($cars)){
                file_put_contents('access_log', '更新:'.json_encode($cars) . PHP_EOL, FILE_APPEND);

            }
            return json_encode(['type' => 'update', 'data' => $cars]);
        }


    }


    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $clientName = $conn->httpRequest->getUri()->getQuery();
        $this->addClient($clientName);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {

        $currentClient = $from->httpRequest->getUri()->getQuery();
        $cars = json_decode($msg, true);
        $httpData = [];
        foreach ($cars as $car) {
            if(isset($car['status']) && $car['status'] == 1) {
                continue;
            }
            $httpData[] = [
                'plate' => $car['plate'],
            ];
        }
        $responseData = $this->post_request('http://localhost/wechat/Parking/delete', json_encode($httpData));
    }

    public function onClose(ConnectionInterface $conn)
    {
        // 移除连接
        $this->clients->detach($conn);
        $clientName = $conn->httpRequest->getUri()->getQuery();
        $this->delClient($clientName);
        print_r($clientName . '断开连接');
        print_r($this->getClients());

    }

    public
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        // 处理错误
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public
    function post_request($url, $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
//            CURLOPT_URL => 'http://localhost:5000/index.php/wechat/Parking/update',
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, 1);

    }

    public function getClients()
    {
        $clients = $this->redis->get('client');
        return $clients;
    }

    public function addClient($client)
    {
        $clients = $this->getClients();
        print_r('$clients');
        print_r($clients);
        if (!empty($clients)) {
            if (!in_array($client, $clients)) {
                $clients[] = $client;
            }
        } else {
            $clients[] = $client;
        }

        $this->saveClients($clients);
    }

    public function delClient($client)
    {
        $clients = $this->getClients();
        $index = array_search($client, $clients);
        if ($index !== false) {
            unset($clients[$index]);
            // 重置数组索引
            $clients = array_values($clients);
        }
        $this->saveClients($clients);
    }

    public function saveClients($clients)
    {
        $this->redis->set('client', $clients);
    }
}