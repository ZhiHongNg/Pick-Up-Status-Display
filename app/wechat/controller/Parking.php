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
        $parkingModel = new \app\common\model\Parking();
        $insertDatas = [];
        foreach ($carStatus as $car) {
            if (!$this->isLicensePlate($car['plate'])) continue;
            $entryInfo = Db::table('he_entry')->where('inCamId', '=', intval($car['camId']))->find();
            if (!empty($entryInfo)) {
                //car in
                $car['status'] = 1;
                $car['entry_id'] = $entryInfo['id'];
                $car['timestamp'] = $car['time'];
                (new \app\common\model\Parking())->save([
                    'plate' => $car['plate'],
                    'entry_id' => $entryInfo['id'],
                    'timestamp' => $car['time'],
                    'status' => 1,
                    'is_send' => 0,
                ]);
                $car['id'] = $this->model->id;
                $this->redis->set('car_in_' . $car['plate'], json_encode($car));

            } else {
                if($car['camId']==64||$car['camId']==65){

                }
                $entryInfo = Db::table('he_entry')->where('outCamId', '=', intval($car['camId']))->find();
                if (!empty($entryInfo)) {
                    $car['entry_id'] = $entryInfo['id'];
                    $car['status'] = 2;
                    $car['timestamp'] = $car['time'];
                    $carInStatus = $this->model->where('plate', $car['plate'])->where('status',1)->find();

                    $carInStatus->status = 2;
                    $carInStatus->save();
                    $car['id'] = $carInStatus->id;
                    $this->redis->set('car_out_' . $car['plate'], json_encode($car));

                }

            }

        }

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

    public function isLicensePlate($plate)
    {
        //9 10
        $parkingModel = new \app\common\model\Parking();
        if (strlen($plate) === 9 || strlen($plate) === 10) {
            $provinces = $parkingModel->getProvinces();
            $flag = in_array(mb_substr($plate, 0, 1), $provinces);
            return $flag;
        } else {
            return false;
        }
    }
}