<?php
function getWeChatImageUrl($image) {
    if($image === null || $image == '') return null;
    if(preg_match('/^http/i', $image)) {
        return $image; 
    }
    return 'http://meirixianguo.com/sst/Public/Wechat/images/'.$image;
}
function productIdtoInfo($productId, $openId) {
    $product = M('Product')->find($productId);
    $register = D('Register')->getUserRegister($openId);
    switch($register['type']) {
    case 5:
        $price = $product['price1'];
        break;
    case 6:
        $price = $product['price2'];
        break;
    case 7:
        $price = $product['price3'];
        break;
    default:
        $price = 0;
        break;
    }
    if($product != null){
        return $product['name'] .' 单价:'. $price.'元';
    } else {
        return '错误商品';
    }
}

function pushText($text) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://avengert.wicp.net:1281/Service1.asmx/SetSendTextInfo?p_strText=".$text);
    $output = curl_exec($ch);
    curl_close($ch);
}
