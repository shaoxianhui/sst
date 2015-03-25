<?php
namespace Wechat\Model;
use Think\Model;
class RegisterModel extends TableModel {
    public function __construct()
    {
        parent::__construct();
        $this->search_column = 'phone';
    }

    protected function filter(&$register) {
        for($i = 0; $i < count($register); $i++) {
            $register[$i]['pr_ci_co'] = $register[$i]['pr'].$register[$i]['ci'].$register[$i]['co'];
            $register[$i]['installDate'] = substr($register[$i]['installDate'], 0, -3);
            $register[$i]['companyDate'] = substr($register[$i]['companyDate'], 0, -3);
            $register[$i]['workDate'] = substr($register[$i]['workDate'], 0, -3);
        }
    }

    public function getRegister($type, $start = 0, $length = 10, $order = 'ctime desc', $search = null) {
        $page = $start / $length + 1;
        $map['type'] = $type;

        if($search != null) {
            $map[$this->search_column] = array('like', "%$search%");
            $rows = $this->where($map)->order($order)->page($page.','.$length)->select();
        } else {
            $rows = $this->where($map)->order($order)->page($page.','.$length)->select();
        }
        if($rows === null)
            return array();
        $this->filter($rows);
        return $rows;
    }

    public function getRegisterCount($type, $search) {
        $map['type'] = $type;
        if($search != null) {
            $map[$this->search_column] = array('like', "%$search%");
        }
        return $this->where($map)->count();
    }
}
