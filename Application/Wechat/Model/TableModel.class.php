<?php
namespace Wechat\Model;
use Think\Model;
class TableModel extends Model {
    public function getCount($search = null) {
        return $this->count();
    }

    public function get($start = 0, $length = 10, $order = 'ctime desc', $search = null) {
        $page = $start / $length + 1;
        $rows = $this->order($order)->page($page.','.$length)->select();
        if($rows === null)
            return array();
        $this->filter($rows);
        return $rows;
    }
    
    protected function filter(&$rows) {

    }
}
