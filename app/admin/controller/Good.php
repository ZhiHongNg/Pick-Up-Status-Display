<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 商品管理
 */
class Good extends Backend
{
    /**
     * Good模型对象
     * @var object
     * @phpstan-var \app\admin\model\Good
     */
    protected object $model;

    protected array|string $preExcludeFields = ['id', 'create_time', 'update_time'];

    protected string|array $quickSearchField = ['id'];

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \app\admin\model\Good;
        $this->request->filter('clean_xss');
    }


    /**
     * 若需重写查看、编辑、删除等方法，请复制 @see \app\admin\library\traits\Backend 中对应的方法至此进行重写
     */
}