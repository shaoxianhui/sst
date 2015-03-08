<?php
function getWeChatImageUrl($image) {
    if($image === null || $image == '') return null;
    if(preg_match('/^http/i', $image)) {
        return $image; 
    }
    return 'http://meirixianguo.com/sst/Public/Wechat/images/'.$image;
}
