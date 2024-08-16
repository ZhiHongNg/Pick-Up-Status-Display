<?php

namespace app\admin\model;

use think\Model;

/**
 * Category
 */
class Category extends Model
{
    // 表名
    protected $name = 'category';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

}