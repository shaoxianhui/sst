<?php
namespace Admin\Controller;
use Think\Controller;
class PageController extends Controller {
	public function __construct() {
        parent::__construct();
        if(!D('Admin')->isLogged()) {
            $this->redirect('Admin/Index/login');
        }
    }

    public function user($openId = null) {
        if($openId == null)
            $this->error('查无此人！');
        $user = D('Wechat/User')->getUser($openId);
        $register = D('Wechat/Register')->getUserRegister($openId);
        $this->assign('user', $user);
        $this->assign('register', $register);
        $this->display();
    }
}
