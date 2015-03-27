<?php
namespace Wechat\Model;
use Think\Model;
class NewsModel extends TableModel {
    protected function filter(&$news) {
        for($i = 0; $i < count($news); $i++) {
            $news[$i]['picUrl'] = '<a href="'.$news[$i]['picUrl'].'">点我浏览图片</a>';
            $news[$i]['url'] = '<a href="'.$news[$i]['url'].'">点我浏览内容</a>';
        }
    }

    public function getNews($list) {
        $news = array();
        $count = 0;
        foreach($list as $l) {
            $n = $this->where('id='.$l)->find();
            $des = explode('</br>', $n['description']);
            $news[$count] = array(
                'Title' => $n['title'],
                'Description'=> $des[0],
                'PicUrl'=> getWeChatImageUrl($n['picUrl']),
                'Url'=> $n['url']
            );
            $count = $count + 1;
        }
        return $news;
    }

    public function callback_edit($id, &$data) {
        preg_match('/"(http:.*)"/', $data['picUrl'], $m);
        if(count($m) == 2){
            $data['picUrl'] = $m[1];
        }
        preg_match('/"(http:.*)"/', $data['url'], $m);
        if(count($m) == 2){
            $data['url'] = $m[1];
        }
    }
}
