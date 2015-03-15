<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class CooperationController extends TemplateController {
    public function __construct()
    {
        parent::__construct();
        $this->model = D('Wechat/Cooperation');
    }
}
