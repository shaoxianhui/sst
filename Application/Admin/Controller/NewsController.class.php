<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class NewsController extends TemplateController {
    public function __construct()
    {
        parent::__construct();
        $this->model = D('Wechat/News');
    }
}
