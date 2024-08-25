<?php

namespace app\VIID\controller;

use app\common\controller\Frontend;
use SimpleXMLElement;
use think\cache\driver\Redis;
use think\facade\Db;

class System extends Frontend
{
    protected array $noNeedLogin = ['*'];

    protected array $noNeedPermission = ['*'];

    protected object $model;
    protected object $redis;


    public function initialize(): void
    {
        parent::initialize();

        $this->model = new \app\common\model\Parking();

        $this->redis = new Redis();

    }
    public function clear()
    {
        $carKeys = $this->redis->keys('*');
        foreach ($carKeys as $key) {
            if ($key === 'client') continue;
            $this->redis->del($key);
        }
    }
    public function Keepalive()
    {
        echo json_encode(['ResponseStatusObject' => ['RequestURL' => '/VIID/System/Register', 'StatusCode' => 0, 'StatusString' => 'success', 'Id' => '45010001491119000101', 'LocalTime' => date('Y-m-d H:i:s')]]);
        exit;
    }
        public function Register()
    {



// 返回内容

//        header('Content-Type: text/json; charset=utf-8');
        $raw_data = file_get_contents('php://input');
        if(!$this->redis->get('DeviceId')){
            http_response_code(401);
            $this->redis->set('DeviceId', 45010001491119000101);
            echo json_encode([
                'error' => 'Unauthorized',
                'message' => 'You are not authorized to access this resource.'
            ]);
            exit;

        }else{
            echo json_encode(['ResponseStatusObject' => ['RequestURL' => '/VIID/System/Register', 'StatusCode' => 0, 'StatusString' => 'success', 'Id' => '45010001491119000101', 'LocalTime' => date('Y-m-d H:i:s')]]);
            exit;
        }


        // 示例数据
        $requestURL = "http://192.168.31.16/VIID/System/Register";
        $statusCode = 0;
        $statusString = '';
        $id = $raw_data['RegisterObject']['DeviceID'];
        $localTime = date('c'); // 使用ISO 8601日期格式

// 返回XML响应
        $this->createXMLResponse($requestURL, $statusCode, $statusString, $id, $localTime);

    }

    public function createXMLResponse($requestURL, $statusCode, $statusString = "", $id = null, $localTime = null)
    {
        // 创建XML文档对象
        $xml = new SimpleXMLElement('<ResponseStatus/>');

        // 添加元素
        $xml->addChild('RequestURL', $requestURL);
        $xml->addChild('StatusCode', $statusCode);
        $xml->addChild('StatusString', $statusString);

        // Optional elements
        if ($id !== null) {
            $xml->addChild('Id', $id);
        }
        if ($localTime !== null) {
            $xml->addChild('LocalTime', $localTime);
        }

        // 输出XML
        echo $xml->asXML();
    }
}
