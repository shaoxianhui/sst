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
}
