<?php
namespace Wechat\Model;
use Think\Model;
class RegisterModel extends TableModel {
    protected function filter(&$register) {
        for($i = 0; $i < count($register); $i++) {
            $register[$i]['pr_ci_co'] = $cooperation[$i]['pr'].$cooperation[$i]['ci'].$cooperation[$i]['co'];
        }
    }

    public function getRegister($type, $start = 0, $length = 10, $order = 'ctime desc', $search = null) {
        $page = $start / $length + 1;
        $where['type'] = $type;
        $rows = $this->where($where)->order($order)->page($page.','.$length)->select();
        if($rows === null)
            return array();
        $this->filter($rows);
        return $rows;
    }

    public function getRegisterCount($type) {
        $where['type'] = $type;
        return $this->where($where)->count();
    }
}
