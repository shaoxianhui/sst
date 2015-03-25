<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class FcodeController extends TemplateController {
    public function __construct()
    {
        parent::__construct();
        $this->model = D('Wechat/Fcode');
    }

    public function createFcode($num, $belongto) {
        $this->model->genFcode($num, 6, $belongto);
        $result['success'] = true;
        $this->ajaxReturn($result);
    }
}
