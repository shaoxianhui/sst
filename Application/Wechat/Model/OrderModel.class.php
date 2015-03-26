<?php
namespace Wechat\Model;
use Think\Model\RelationModel;
class OrderModel extends TableModel {
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
                $productId = $orders[$i]['items'][$j]['productId'];
                $product = D('Wechat/Product')->find($productId);
                if($product != null){
                    $products .= $product['name'];
                } else {
                    $products .= '错误商品';
                }
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
}
