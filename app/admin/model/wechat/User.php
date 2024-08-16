<?php

namespace app\admin\model\wechat;

use think\Model;

/**
 * User
 */
class User extends Model
{
    // 表名
    protected $name = 'wechat_user';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

}