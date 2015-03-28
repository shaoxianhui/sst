<?php
namespace Wechat\Model;
use Think\Model\RelationModel;
class TableModel extends RelationModel {
    public $search_column;
    public $map = array();
    public function getCount($search = null) {
        if($search != null) {
            $cs = explode(',', $this->search_column);
            $map['_logic'] = 'or';
            foreach($cs as $c) {
                $map[$c] = array('like', "%$search%");
            }
            $rows = $this->where($map)->count();
        } else {
            return $this->count();
        }
    }

    public function get($start = 0, $length = 10, $order = 'ctime desc', $search = null) {
        $page = $start / $length + 1;
        if($search != null) {
            $cs = explode(',', $this->search_column);
            $fi['_logic'] = 'or';
            foreach($cs as $c) {
                $fi[$c] = array('like', "%$search%");
            }
            $map['_complex'] = $fi;
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

    public function callback_edit($id, &$data) {

    }

    public function callback_create(&$data) {

    }

    public function callback_delete($id) {

    }

    public function toString($data, $map = null, $end = "\n") {
        if($map == null)
            $map = $this->map;
        $str = '';
        foreach($data as $key => $value) {
            if($value === '' || $value === null)
                continue;
            if(array_key_exists($key, $map))
            {
                if(array_key_exists('name', $map[$key]))
                {
                    $str .=  $map[$key]['name'].':';
                }
                else
                {
                    $str .=  $key.':';
                }
                if(array_key_exists($value, $map[$key]))
                {
                    $str .= $map[$key][$value].$end;
                }
                else
                {
                    $str .= $value.$end;
                }
            }
            else
            {
                $str .=  $key.':'.$value.$end;
            }
        }
        return $str;
    }
}
