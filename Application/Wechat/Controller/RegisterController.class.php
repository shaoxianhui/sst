<?php
namespace Wechat\Controller;
use Think\Log;
class RegisterController extends QyController {
    public function __construct()
    {
        $this->agentid = 1;
        $this->name = 'register';
        parent::__construct();
    }
}
