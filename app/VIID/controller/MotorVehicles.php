<?php

namespace app\VIID\controller;

use app\common\controller\Frontend;
use think\cache\driver\Redis;

class MotorVehicles extends Frontend
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

    public function index()
    {
        ini_set('post_max_size', '50M');
        ini_set('upload_max_filesize', '50M');
        $raw_data = file_get_contents('php://input');
        $raw_data  =json_decode($raw_data,true);
        $cars = [];
        if(!empty($raw_data)){
            if(isset($raw_data['MotorVehicleListObject'])){
                foreach ($raw_data['MotorVehicleListObject']['MotorVehicleObject'] as $v){
                    file_put_contents('video_to_php', json_encode($raw_data) . PHP_EOL, FILE_APPEND);
                    $cars[] = ['PlateReliability'=>$v['PlateReliability'],'PlateNo'=>$v['PlateNo'],'PassTime'=>$v['PassTime'],'DeviceID'=>$v['DeviceID']];
                }
                if(!empty($cars)){
                    $carStatus = [];
                    foreach ($cars as $car){
                        $carStatus[] = ['time'=>time(),'plate'=>$car['PlateNo'],'camId'=>$car['DeviceID']];
                    }
                    file_put_contents('$carStatus', '' . json_encode($carStatus) . PHP_EOL, FILE_APPEND);

                    $this->model->handleCar($carStatus);
                }


            }
        }
    }

}
