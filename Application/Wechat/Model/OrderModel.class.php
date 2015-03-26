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
}
