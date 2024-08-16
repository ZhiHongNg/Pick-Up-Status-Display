<?php

namespace app\admin\model;

use app\admin\model\order\Item;
use think\Model;

/**
 * Order
 */
class Order extends Model
{
    // 表名
    protected $name = 'order';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    protected $append = [];


    public function items(): \think\model\relation\hasMany
    {
        return $this->hasMany(Item::class, 'order_id', 'id');
    }


}