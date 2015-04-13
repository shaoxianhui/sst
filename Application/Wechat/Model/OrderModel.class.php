<?php
namespace Wechat\Model;
use Think\Model\RelationModel;
class OrderModel extends TableModel {
    public $map = array('name' => array('name' => '姓名'),
                        'phone' => array('name' => '联系电话'),
                        'location' => array('name' => '发货地址'),
                        'company' => array('name' => '公司'),
                        'code' => array('name' => '订单号'),
                        'amount' => array('name' => '订单金额', 'post' => '元')
    );
    protected $_link = array(
        'OrderItem' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'OrderItem',
            'foreign_key' => 'orderId',
            'mapping_name' => 'items',
            'mapping_order' => 'id desc'
        )
    );

    protected function filter(&$orders) {
        for($i = 0; $i < count($orders); $i++) {
            $user = D('Wechat/User')->getUser($openId);
            if($user != null){
                $orders[$i]['nickname'] = $user['nickname'];
            } else {
                $orders[$i]['nickname'] = '匿名';
            }
            $products = '';
            for($j = 0; $j < count($orders[$i]['items']); $j++) {
                $products .= $orders[$i]['items'][$j]['productName'];
                $products .= ' 单价：'.$orders[$i]['items'][$j]['productPrice'].'元';
                $products .= ' '.$orders[$i]['items'][$j]['quantity'].'个</br>';
            }
            $orders[$i]['products'] = $products;
        }
    }

    public function callback_edit($id, $data) {
        $order = $this->find($id);
        if($order['status'] != $data['status'])
        {
            switch($data['status']) {
            case 1:
                $first = '商品收款确认通知';
                $schedule = '订单号'.$order['code'].'收款成功';
                $remark = '请点击菜单“订单查询”查看详情';
                break;
            case 2:
                $first = '商品备货通知';
                $schedule = '订单号'.$order['code'].'备货';
                $remark = '请点击菜单“订单查询”查看详情';
                break;
            case 3:
                $first = '商品发货通知';
                $schedule = '订单号'.$order['code'].'发货';
                $remark = $data['express'].'('.$data['expressCode'].')，'.'请点击菜单“订单查询”查看详情';
                break;
            case 4:
                $first = '商品交易完成通知';
                $schedule = '订单号'.$order['code'].'交易完成';
                $remark = '感谢您对鈊龙控制卡的支持，订单详情请点击菜单“订单查询”查看';
                break;
            case 5:
                $first = '订单取消通知';
                $schedule = '订单号'.$order['code'].'交易取消';
                $remark = '欢迎您再次下单，并请在3天内完成付款，以免订单超时自动取消。';
                break;
            }
            A('Wechat/Index')->sendTemplate(
                $order['openId'],
                '',
                $first,
                $schedule,
                $remark
            );
        }
    }
    public function __construct()
    {
        parent::__construct();
        $this->search_column = 'name,phone,company';
    }
}
