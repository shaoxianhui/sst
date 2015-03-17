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
}
