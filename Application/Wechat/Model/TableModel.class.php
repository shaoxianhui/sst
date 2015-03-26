<?php
namespace Wechat\Model;
use Think\Model\RelationModel;
class TableModel extends RelationModel {
    public $search_column;
    public function getCount($search = null) {
        if($search != null) {
            $map[$this->search_column] = array('like', "%$search%");
            $rows = $this->where($map)->count();
        } else {
            return $this->count();
        }
    }

    public function get($start = 0, $length = 10, $order = 'ctime desc', $search = null) {
        $page = $start / $length + 1;
        if($search != null) {
            $map[$this->search_column] = array('like', "%$search%");
            $rows = $this->relation(true)->where($map)->order($order)->page($page.','.$length)->select();
        } else {
            $rows = $this->relation(true)->order($order)->page($page.','.$length)->select();
        }
        if($rows === null)
            return array();
        $this->filter($rows);
        return $rows;
    }
    
    protected function filter(&$rows) {

    }
}
