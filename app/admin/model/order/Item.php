<?php

namespace app\admin\model\order;

use think\Model;

/**
 * Item
 */
class Item extends Model
{
    // 表名
    protected $name = 'order_item';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;


    public function order(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\admin\model\Order::class, 'order_id', 'id');
    }
}