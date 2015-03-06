<?php
namespace Wechat\Controller;
use Think\Controller;
use Think\Log;
class IndexController extends Controller {
	private $wechat;
	public function __construct() {
        parent::__construct();
        $this->wechat = new \Org\Wechat_PHP\TPWechat(C('WECHAT'));
    }
    public function index() {
		$this->wechat->valid();
        $type = $this->wechat->getRev()->getRevType();
        switch($type) {
        case \Org\Wechat_PHP\Wechat::MSGTYPE_TEXT:           
            D('TextMessage')->addTextMessage($this->wechat->getRevFrom(), $this->wechat->getRevContent(), $this->wechat->getRevCtime());
            $this->wechat->text("text message!")->reply();
            break;
        case \Org\Wechat_PHP\Wechat::MSGTYPE_EVENT:
            $event = $this->wechat->getRevEvent();
            if($event !== false) {
                switch(strtolower($event['event'])) {
                case 'subscribe':
					$this->wechat->text("subscribe event!")->reply();
                    break;
                case 'unsubscribe':
					$this->wechat->text("unsubscribe event!")->reply();
                    break;
                case 'scan':
					$this->wechat->text("scan event!")->reply();
                    break;
                case 'click':
                    switch($event['key']) {
                    case 'MENU_KEY':
						$this->wechat->text("menu key event!")->reply();
						break;
                    default:
                        $this->wechat->text("default menu key event!")->reply();
						break;
                    }
                    break;
                default:
					$this->wechat->text("default event!")->reply();
					break;
                }
            }
            break;
        case \Org\Wechat_PHP\Wechat::MSGTYPE_IMAGE:
			$this->wechat->text("image message!")->reply();
            break;
        default:
            $this->wechat->text("default rev type!")->reply();
			break;
        }
    }
}
