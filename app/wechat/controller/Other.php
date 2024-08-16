<?php

namespace app\wechat\controller;

use app\common\controller\Frontend;
use app\common\library\Upload as U;

class Other extends Frontend
{
    protected array $noNeedLogin = ['*'];

    protected array $noNeedPermission = ['*'];

    protected object $model;


    public function initialize(): void
    {
        parent::initialize();
    }

    public function upload()
    {
        $file = $this->request->file('file');

        if (!$file) {
            return $this->internetError('没有上传文件');
        }
        try {
            // 上传文件到指定目录
            $upload = new U($file);
            $attachment = $upload->upload(null, 0, 1);
        } catch (\Exception $e) {
            return $this->internetError('上传失败');
        }
        return $this->success('', $attachment);
    }

    public function show()
    {
        $id = $this->request->param("id");
        return $this->success('', $this->model->find($id));
    }
}