<?php
namespace Wechat\Model;
use Think\Model;
class QuestionModel extends TableModel {
    public $map = array('name' => array('name' => '姓名'),
                        'sex' => array('name' => '性别', '1' => '男', '0' => '女'),
                        'phone' => array('name' => '联系电话'),
                        'application' => array('name' => '显示屏用途'),
                        'description' => array('name' => '提交内容')
    );
}
