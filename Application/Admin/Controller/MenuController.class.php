<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class MenuController extends TemplateController {
    public function __construct() {
        parent::__construct();
        $this->model = D('Wechat/Menu');
    }
    public function updateMenu() {
        $menus = $this->model->getMenus();
        A('Wechat/Index')->updateMenu($menus);
        $result['success'] = true;
        $this->ajaxReturn($result);
    }
}
