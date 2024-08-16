<?php

namespace app\wechat\controller;

use app\admin\model\order\Item;
use app\common\controller\Frontend;
use think\facade\Db;

class Order extends Frontend
{
    protected array $noNeedLogin = ['*'];

    protected array $noNeedPermission = ['*'];

    protected object $model;
    protected object $order_item_model;


    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \app\admin\model\Order();
        $this->order_item_model = new Item();
        $this->checkUser(['cancelOverdueOrders']);
    }

    public function store()
    {
        $params = $this->request->param();
        $goods = $this->request->param('goods');
        $totalAmount = 0;
        foreach ($goods as $good) {
            $totalAmount = $totalAmount + $good['price'] * $good['number'];
        }
        $orderData = [
            'openid' => $this->openid, // 根据需求获取 openid
            'amount' => $totalAmount, // 根据需求获取订单金额
            'name' => $params['name'],
            'phone' => $params['phone'],
            'address' => $params['address'],
            'pay_sn' => '', // 根据需求生成支付单号
            'remark' => $params['remark'],
            'status' => 0, // 初始状态为待支付
            'cancel_reason' => '',
            'id_number' => $params['idnumber'],
            'id_name' => $params['realname'],
            'id_front_image' => $params['idFront'],
            'id_back_image' => $params['idBack'],
            'pay_time' => null,
        ];
        Db::startTrans();
        try {
            $this->model->save($orderData);
            $orderItems = [];
            foreach ($goods as $good) {
                $goodInfo = (new \app\admin\model\Good())->find($good['id']);
                if (empty($goodInfo)) throw new \Exception($good['name'] . '已下架');
                if ($goodInfo['stock'] < $good['number']) {
                    throw new \Exception($good['name'] . '库存不足' . $good['number']);
                }
                $orderItems[] = [
                    'order_id' => $this->model->id,
                    'name' => $good['name'],
                    'number' => $good['number'],
                    'price' => $good['price'],
                    'image' => $good['image'],
                ];
            }
            $this->order_item_model->insertAll($orderItems);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            return $this->internetError($exception->getMessage());
        }
        return $this->success('', $this->model->id);
    }

    public function index()
    {

        $list = $this->model->select();
        return $this->success('', $list);
    }

    public function show()
    {
        $id = $this->request->param("id");
        $order = $this->model->find($id);
        $order->items = $order->items;
        return $this->success('', $order);
    }

    public function cancelOverdueOrders()
    {
        // 当前时间减去30分钟的时间戳
        $timeLimit = time() - 30 * 60;

        // 查询 status=0 且 create_time 超过30分钟的订单
        $orders = $this->model->where('status', 0)->where('create_time', '<', $timeLimit)->select();
        // 遍历订单并更新状态和取消原因
        foreach ($orders as $order) {

            $this->model->where('id', $order->id)
                ->update([
                    'status' => 5, // 设定为已取消状态
                    'cancel_reason' => '超时未支付'
                ]);
        }

        return '处理完成，共取消' . count($orders) . '个订单。';
    }
}