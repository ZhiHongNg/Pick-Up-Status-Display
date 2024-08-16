<?php

namespace app\admin\model;

use think\Model;

/**
 * Good
 */
class Good extends Model
{
    // 表名
    protected $name = 'good';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;


    public function getDetailAttr($value): string
    {
        return !$value ? '' : htmlspecialchars_decode($value);
    }
}