<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index() {
        if(D('Admin')->isLogged()) {
            $this->redirect('Admin/Index/base');
        } else {
            $this->redirect('Admin/Index/login');
        }
    }

    public function login() {
        if(D('Admin')->isLogged()) {
            $this->redirect('Admin/Index/base');
        }
        $this->display();
    }

    public function doLogin($adminName, $password, $remember = false) {
        if(D('Admin')->login($adminName, $password, $remember)) {
            if(session('?jump_url')) {
                $jump_url = session('jump_url');
            } else {
                $jump_url = 'base';
            }
            session('jump_url', null);
            $this->success('登陆成功', $jump_url);
        } else {
            $this->error('登陆失败', 'login', 3);
        }
    }
	
    public function doLogout() {
        D('Admin')->logout();
        $this->success('退出成功', 'login');
    }

    public function test() {
    }
}
