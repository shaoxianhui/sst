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
            $mc = preg_match_all('/\d+/i', $this->wechat->getRevContent(), $ms);
            if($mc > 0) {
                $paras = implode(' ', $ms[0]);
                exec('/var/www/sst/Script/sum '.$paras, $res);
                $this->wechat->text("echo: \n".implode("\n", $res))->reply();
            }
            $this->wechat->text(D('Text')->getText(1))->reply();
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

    public function updateMenu() {
        $menus = D('Menu')->getMenus();
        dump($menus);
        $result = $this->wechat->createMenu($menus);
        echo $result;
    }
}
