<?php
namespace Wechat\Model;
use Think\Model;
class TextModel extends TableModel {
    public function getText($id) {
        if(S('textMessage_'.$id))
            return S('textMessage_'.$id);
        $text = $this->where('id='.$id)->find();
        if($text == true) {
            S('textMessage_'.$id, $text['message'], 3600);
            return $text['message'];
        }
    }

    public function clearCache($id) {
        S('textMessage_'.$id, null);
    }
}
