<?php
namespace Wechat\Controller;
use Think\Controller;
use Think\Log;
class RegisterController extends Controller {
    private $wechat;
    public function __construct()
    {
        parent::__construct();
        $options = array(
		'token'=>'xinlong',
		'appid'=>'wxdc3d538b5cd41cb5',
		'appsecret'=>'77TshqVv9nTtEIyeJjKciasn8_M7UuuZLsM-VypwdAY-rL3eQdwSf64zOOrDlzz6',
		'encodingaeskey'=>'LDA7WEsWOtjrEwFpNaX5ewdo2G2pn1zT5qW5CGRitvX',
		'agentid'=>'1',
		'debug' => true,
        );
        $this->wechat = new \Org\Wechat_PHP\TPQyWechat($options);
    }

    public function index() {
        $this->wechat->valid();
        $type = $this->wechat->getRev()->getRevType();
        switch($type) {
        case \Org\Wechat_PHP\QyWechat::MSGTYPE_TEXT:
            $this->wechat->text('hello register!')->reply();
            break;
        case \Org\Wechat_PHP\QyWechat::MSGTYPE_EVENT:
            $event = $this->wechat->getRevEvent();
            if($event !== false) {
                switch(strtolower($event['event'])) {
                case 'subscribe':
                    break;
                case 'unsubscribe':
                    break;
                case 'scan':
                    break;
                case 'click':
                    switch($event['key']) {
                    case 'TASK_TASK':
                        $this->wechat->text('hello register!')->reply();
                        break;
                    }
                    break;
                case 'location':
                    break;
                default:
                }
            }
            break;
        case \Org\Wechat_PHP\QyWechat::MSGTYPE_IMAGE:
            $this->wechat->text('hello register!')->reply();
            break;
        default:
            $this->wechat->text('hello register!')->reply();
        }
    }

    public function publish($content) {
        $message = array( "touser" => "@all",
            "safe" => "0",
            "agentid" => "1",
            "msgtype" => "text",
            "text" => array(
                "content" => $content,
        ));
        $this->wechat->sendMessage($message);
    }
}
