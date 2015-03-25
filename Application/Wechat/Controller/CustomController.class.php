<?php
namespace Wechat\Controller;
use Think\Log;
class CustomController extends QyController {
    public function __construct()
    {
        $this->agentid = 4;
        $this->name = 'custom';
        parent::__construct();
    }
}
