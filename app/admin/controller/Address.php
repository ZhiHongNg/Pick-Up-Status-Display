<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 地址管理
 */
class Address extends Backend
{
    /**
     * Address模型对象
     * @var object
     * @phpstan-var \app\admin\model\Address
     */
    protected object $model;

    protected array|string $preExcludeFields = ['id', 'create_time', 'update_time'];

    protected string|array $quickSearchField = ['id'];

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \app\admin\model\Address;
    }


    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \app\admin\library\traits\Backend 中对应的方法至此进行重写
     */
}