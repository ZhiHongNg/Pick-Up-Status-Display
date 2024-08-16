<?php

namespace app\admin\controller\wechat;

use app\common\controller\Backend;

/**
 * 用户管理
 */
class User extends Backend
{
    /**
     * User模型对象
     * @var object
     * @phpstan-var \app\admin\model\wechat\User
     */
    protected object $model;

    protected array|string $preExcludeFields = ['id', 'city', 'country', 'gender', 'language', 'access_token', 'expire_time', 'session_key', 'create_time', 'update_time'];

    protected string|array $quickSearchField = ['id'];

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \app\admin\model\wechat\User;
    }


    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \app\admin\library\traits\Backend 中对应的方法至此进行重写
     */
}