<?php
function getWeChatImageUrl($image) {
    if($image === null || $image == '') return null;
    if(preg_match('/^http/i', $image)) {
        return $image; 
    }
    return 'http://meirixianguo.com/sst/Public/Wechat/images/'.$image;
}
function productIdtoName($productId) {
    $product = M('Product')->find($productId);
    if($product != null){
        return $product['name'];
    } else {
        return '错误商品';
    }
}
