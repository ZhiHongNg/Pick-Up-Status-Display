<?php

namespace app\wechat\controller;

use app\admin\model\Address as AddressesModel;
use app\common\controller\Frontend;

class Address extends Frontend
{
    protected array $noNeedLogin = ['*'];

    protected array $noNeedPermission = ['*'];
    protected object $model;


    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \app\admin\model\Address;
        $this->checkUser();
    }

    public function index()
    {
        $address = AddressesModel::where('wechat_user_id', $this->wechatUser->id)->select();
        return $this->success('', $address);
    }


    public function update(){
        $id = $this->request->param('id');
        $address = $this->model->where('id', $id)->where('wechat_user_id', $this->wechatUser->id)->find();
        $address->name = $this->request->param('name');
        $address->phone = $this->request->param('phone');
        $address->address = $this->request->param('address');
        if($address->save()){
            return $this->success('修改成功');
        }else{
            return $this->internetError('操作失败');
        }
    }
    public function store()
    {
        $flag = $this->model->save([
            'wechat_user_id' => $this->wechatUser->id,
            'name' => $this->request->param('name'),
            'phone' => $this->request->param('phone'),
            'address' => $this->request->param('address'),
        ]);
        if ($flag) {
            return $this->success('添加成功');
        } else {
            return $this->internetError('参数错误');
        }
    }
    public function destroy()
    {
        $id = $this->request->param('id');
        $flag = $this->model->where('id', $id)->where('wechat_user_id', $this->wechatUser->id)->delete();
        if ($flag) {
            return $this->success();
        }else{
            return $this->internetError('操作失败');
        }
    }


}