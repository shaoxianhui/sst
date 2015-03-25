<?php
namespace Wechat\Controller;
use Think\Log;
class CooperationController extends QyController {
    public function __construct()
    {
        $this->agentid = 3;
        $this->name = 'cooperation';
        parent::__construct();
    }
}
