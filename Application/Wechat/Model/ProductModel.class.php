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
}
