<?php
namespace Wechat\Model;
use Think\Model;
use Org\Util\String;
class FcodeModel extends Model {
    public function genFcode($num, $width) {
        for($i = 0; $i < $num; ) {
            $fcode = String::randString($width);
            $where['fcode'] = $fcode;
            $ret = $this->where($where)->find();
            if($rec == null) {
                $data['fcode'] = $fcode;
                $data['genTime'] = time();
                $this->data($data)->add();
                $i = $i + 1;
            }
        }
    }
    public function useFcode($code) {
        $where['fcode'] = $code;
        $fcode = $this->where($where)->find();
        if($fcode == null || $fcode['useTime'] != 0) {
            return false;
        } else {
            $fcode['useTime'] = time();
            $this->data($fcode)->save();
            return true;
        }
    }
}
