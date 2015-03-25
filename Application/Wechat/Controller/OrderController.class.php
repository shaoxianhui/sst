<?php
namespace Wechat\Controller;
use Think\Log;
class OrderController extends QyController {
    public function __construct()
    {
        $this->agentid = 5;
        $this->name = 'order';
        parent::__construct();
    }
}
