<?php

namespace app\wechat\controller;

use app\common\controller\Frontend;
use think\cache\driver\Redis;
use think\facade\Db;

class Parking extends Frontend
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

    public function update()
    {
        $cars = $this->request->param();
        foreach ($cars as $car) {
            $this->model->where('id', $car['id'])->update(['is_send' => 1]);
        }
        return $cars;
    }
    public function delete()
    {
        $cars = $this->request->param();
        foreach ($cars as $car) {
            $this->model->where('plate', $car['plate'])->delete();
        }
        return $cars;
    }

    public function index()
    {
        $parkingCars = $this->model->where('status', 1)->where('is_send', 1)->select();
        return $this->success('', $parkingCars);
    }


    public function store()
    {
        file_put_contents('access_log', 'store action' . json_encode($this->request->post()) . PHP_EOL, FILE_APPEND);
        $carStatus = $this->request->post();
        $this->model->handleCar($carStatus);
    }
    public function loadFile()
    {
        $file_path = 'carList';

// 初始化一个数组来存储解析后的数据
        $data_array = [];

// 打开文件以读取
        $file = fopen($file_path, 'r');

// 检查文件是否成功打开
        if ($file) {
            // 逐行读取文件内容
            while (($line = fgets($file)) !== false) {
                // 去除行末的换行符
                $line = trim($line);

                // 跳过空行
                if (empty($line)) {
                    continue;
                }

                // 将每一行的 JSON 数据解析为数组，并将其添加到 $data_array 中
                $json_data = json_decode($line, true);

                // 如果解析成功，添加到数组中
                if ($json_data !== null) {
                    $data_array[] = $json_data;
                } else {
                    echo "无法解析以下内容为 JSON: $line\n";
                }
            }

            // 关闭文件
            fclose($file);
        } else {
            echo "无法打开文件: $file_path\n";
        }

// 打印或处理 $data_array 数据
        $this->model->handleCar($data_array[rand(0,count($data_array))]);
    }

    public function entry()
    {
        $entrys = Db::table('he_entry')->whereNotNull('inCamId')->whereNotNull('outCamId')->select();

        return $this->success('', $entrys);
    }

    public function clear()
    {
        file_put_contents('access_log', 'clear action' . PHP_EOL, FILE_APPEND);

        $carKeys = $this->redis->keys('*');
        foreach ($carKeys as $key) {
            if ($key === 'client') continue;
            $this->redis->del($key);
        }
        $this->redis->set('clear', true);
//        Db::table('he_parking')->where('id','>=',1)->delete();


    }

    public function check()
    {

        $carKeys = $this->redis->keys('*');
        $cars = [];
        foreach ($carKeys as $key) {
            $carItem = json_decode($this->redis->get($key), 1);
            $carItem['key'] = $key;
            $cars[] = $carItem;
        }
    }

}