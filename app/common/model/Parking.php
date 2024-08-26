<?php

namespace app\common\model;

use think\cache\driver\Redis;

use think\facade\Db;
use think\Model;

class Parking extends Model
{
    protected $name = 'parking';
    protected $autoWriteTimestamp = true;
    protected $append = [];

    private $provinces = ["京", "津", "沪", "渝", "冀", "豫", "云", "辽", "黑", "湘", "皖", "鲁", "新", "苏", "浙", "赣", "鄂", "桂", "甘", "晋", "蒙", "陕", "吉", "闽", "贵", "粤", "青", "藏", "川", "宁", "琼"];
    private $regions = ["1-8号上车点（A通道）", "9-15号上车点（B通道）"];

    private function getRandomElement($array)
    {
        return $array[array_rand($array)];
    }
    public function getProvinces()
    {
            return $this->provinces;
    }

    private function getRandomNumber($min, $max)
    {
        return rand($min, $max);
    }

    private function getRandomPlate()
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $numbers = "0123456789";
        $plate = "";

        for ($i = 0; $i < 2; $i++) {
            $plate .= $chars[rand(0, strlen($chars) - 1)];
        }
        for ($i = 0; $i < 4; $i++) {
            $plate .= $numbers[rand(0, strlen($numbers) - 1)];
        }
        return $plate;
    }

    private function getEntryTime()
    {

        return date('Y-m-d H:i:s', time());
    }

    public static function getParkingCar()
    {
        $redis = new  Redis();
        $carKeys = $redis->keys('parking_car*');
        $cars = [];
        foreach ($carKeys as $key) {
            $cars[] = json_decode($redis->get($key), 1);
        }
        return $cars;
    }

    public function generateLicensePlates($number = 20)
    {
        $items = [];
        $a = new Redis();


        for ($i = 0; $i < $number; $i++) {
            $province = $this->getRandomElement($this->provinces);
            $isElectric = rand(0, 100) < 20; // 20% chance to be an electric car
            $plate = $this->getRandomPlate();
            $entryTime = $this->getEntryTime();
            $entryId = $this->getRandomNumber(1, 2);
            $entryRegion = $this->getRandomElement($this->regions);
            $entryNumber = $this->getRandomNumber(1, 999999999999999);
            $type = 'in';

            $item = [
                'type' => $type,
                'licensePlate' => $province . $plate,
                'province' => $province,
                'isElectric' => $isElectric,
                'plate' => $plate,
                'entryTime' => $entryTime,
                'entryId' => $entryId,
                'entryRegion' => $entryRegion,
                'entryNumber' => $entryNumber,
                'cache' => $a->get('name')
            ];
            $items[] = $item;
            $redis = new  Redis();
            $redis->set('parking_car' . $entryNumber, json_encode($item));


        }
        $parkingCars = self::getParkingCar();
        if($parkingCars>=50){
            $deleteCount = 4;
        }else{
            $deleteCount = rand(1,3);
        }

        foreach ($parkingCars as $car) {
            $number = $this->getRandomNumber(1, 100);
            if ($number >= 85) {
                $a->del('parking_car' . $car['entryNumber']);
                $car['type'] = 'out';
                $items[] = $car;
            }
        }

        return $items;
    }

    public function handleCar($carStatus)
    {
        $redis = new  Redis();

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
                $car['id'] = $this->id;
                $redis->set('car_in_' . $car['plate'], json_encode($car));

            } else {
                if($car['camId']==64||$car['camId']==65){

                }
                $entryInfo = Db::table('he_entry')->where('outCamId', '=', intval($car['camId']))->find();
                if (!empty($entryInfo)) {
                    $car['entry_id'] = $entryInfo['id'];
                    $car['status'] = 2;
                    $car['timestamp'] = $car['time'];
                    $carInStatus = $this->where('plate', $car['plate'])->where('status',1)->find();

                    $carInStatus->status = 2;
                    $carInStatus->save();
                    $car['id'] = $carInStatus->id;
                    $redis->set('car_out_' . $car['plate'], json_encode($car));

                }

            }

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
