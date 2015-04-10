<?php
namespace Wechat\Model;
use Think\Model;
class ProjectModel extends TableModel {
    public $map = array('name' => array('name' => '姓名'),
                        'sex' => array('name' => '性别', '1' => '男', '0' => '女'),
                        'telephone' => array('name' => '联系电话'),
                        'company' => array('name' => '公司名称或个人'),
                        'pr' => array('name' => '省'),
                        'ci' => array('name' => '市'),
                        'co' => array('name' => '县'),
                        'finishDate' => array('name' => '项目需要完成时间'),
                        'description' => array('name' => '项目描述')
    );
    protected function filter(&$project) {
        for($i = 0; $i < count($project); $i++) {
            $project[$i]['pr_ci_co'] = $project[$i]['pr'].$project[$i]['ci'].$project[$i]['co'];
        }
    }
    public function __construct()
    {
        parent::__construct();
        $this->search_column = 'name,telephone';
    }
}
