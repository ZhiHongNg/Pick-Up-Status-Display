<?php

namespace app\wechat\controller;

use app\common\controller\Frontend;

class Category extends Frontend
{
    protected array $noNeedLogin = ['*'];

    protected array $noNeedPermission = ['*'];
    protected object $model;


    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \app\admin\model\Category();
    }

    public function index()
    {
        $list = $this->model->order('sort desc')->select();
        return $this->success('', $list);
    }
}