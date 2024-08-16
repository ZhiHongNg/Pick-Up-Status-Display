<?php

namespace app\wechat\controller;

use app\common\controller\Frontend;

class Good extends Frontend
{
    protected array $noNeedLogin = ['*'];

    protected array $noNeedPermission = ['*'];

    protected object $model;


    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \app\admin\model\Good();
        $this->checkUser();
    }

    public function index()
    {
        $query = $this->model;
        if($this->request->param('category_id')){
            $query = $query->where('category_id', $this->request->param('category_id'));
        }
        if($this->request->param('ids') !== null){

            $query = $query->where('id', 'in', $this->request->param('ids'));
        }

        $list = $query->select();
        return $this->success('', $list);
    }

    public function show(){
        $id=  $this->request->param("id");
        return $this->success('',$this->model->find($id));
    }
}