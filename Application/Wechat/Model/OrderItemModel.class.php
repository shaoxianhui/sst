<?php
namespace Wechat\Model;
use Think\Model\RelationModel;
class OrderItemModel extends RelationModel {
    protected $_link = array(
        'Order' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Order',
            'foreign_key' => 'orderId',
            'mapping_name' => 'order'
        )
    );
}
