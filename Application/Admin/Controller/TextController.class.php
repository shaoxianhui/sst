<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class TextController extends TemplateController {
    public function __construct()
    {
        parent::__construct();
        $this->model = D('Wechat/Text');
    }
}
