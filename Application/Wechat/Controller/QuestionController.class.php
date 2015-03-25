<?php
namespace Wechat\Controller;
use Think\Log;
class QuestionController extends QyController {
    public function __construct()
    {
        $this->agentid = 2;
        $this->name = 'question';
        parent::__construct();
    }
}
