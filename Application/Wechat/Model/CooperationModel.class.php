<?php
namespace Wechat\Model;
use Think\Model;
class CooperationModel extends TableModel {
    public $map = array('name' => array('name' => '姓名'),
                        'sex' => array('name' => '性别', '1' => '男', '0' => '女'),
                        'telephone' => array('name' => '联系电话'),
                        'company' => array('name' => '公司名称或个人'),
                        'pr' => array('name' => '省'),
                        'ci' => array('name' => '市'),
                        'co' => array('name' => '县'),
                        'location' => array('name' => '联系地址'),
                        'shipments' => array('name' => '预计年出货量')
    );
    protected function filter(&$cooperation) {
        for($i = 0; $i < count($cooperation); $i++) {
            $cooperation[$i]['pr_ci_co'] = $cooperation[$i]['pr'].$cooperation[$i]['ci'].$cooperation[$i]['co'];
        }
    }
    public function __construct()
    {
        parent::__construct();
        $this->search_column = 'name,telephone';
    }
}
