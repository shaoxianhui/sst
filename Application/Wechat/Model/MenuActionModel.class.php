<?php
namespace Wechat\Model;
use Think\Model;
class MenuActionModel extends Model {
    public function action($key, $wechat) {
        $menu = D('Menu')->getMenu($key);
        if($menu == null) {
            $wechat->text(D('Text')->getText(4))->reply();
            return;
        }
        $where['keyId'] = $menu['id'];
        $action = $this->where($where)->find();
        if($action == null) {
            $wechat->text(D('Text')->getText(4))->reply();
            return;
        }
        switch($action['action']) {
        case 'news':
            $wechat->news(D('News')->getNews(explode(',', $action['items'])))->reply();
            break;
        case 'text':
            $wechat->text(D('Text')->getText($action['items']))->reply();
            break;
        default:
            $wechat->text(D('Text')->getText(4))->reply();
            break;
        }
    }
}
