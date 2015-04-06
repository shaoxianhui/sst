<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class AdminController extends TemplateController {
    public function __construct()
    {
        parent::__construct();
    }

    public function changePassword($password, $password1) {
        if(isset($_SESSION['adminname']) && $password == $password1) {
            $map['name'] = $_SESSION['adminname'];
            M('Admin')->where($map)->setField('password', md5($password));
            $this->success('密码设置成功！');
        } else {
            $this->error('密码设置失败！');
        }
    }

}
