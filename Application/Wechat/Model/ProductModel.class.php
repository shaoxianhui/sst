<?php
namespace Wechat\Model;
use Think\Model\RelationModel;
class ProductModel extends TableModel {
    protected function filter(&$products) {
        for($i = 0; $i < count($products); $i++) {
            $products[$i]['imageUrl'] = '<a href="'.$products[$i]['imageUrl'].'">点我浏览图片</a>';
            $products[$i]['url'] = '<a href="'.$products[$i]['url'].'">点我浏览内容</a>';
        }
    }

    public function callback_edit($id, &$data) {
        preg_match('/"(http:.*)"/', $data['imageUrl'], $m);
        if(count($m) == 2){
            $data['imageUrl'] = $m[1];
        }
        preg_match('/"(http:.*)"/', $data['url'], $m);
        if(count($m) == 2){
            $data['url'] = $m[1];
        }
    }
}
