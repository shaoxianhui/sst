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
                    $info = $this->wechat->getUserInfo($this->wechat->getRevFrom());
                    $add_or_update = D('User')->addUser($this->wechat->getRevFrom(), $info['nickname'], $this->wechat->getRevSceneId());
                    if($add_or_update) {
                        $this->wechat->text(D('Text')->getText(2))->reply();
                    } else {
                        $this->wechat->text(D('Text')->getText(3))->reply();
                    }
                    break;
                case 'unsubscribe':
                    D('User')->removeUser($this->wechat->getRevFrom());
                    break;
                case 'scan':
					$this->wechat->text("scan event!")->reply();
                    break;
                case 'click':
                    D('MenuAction')->action($event['key'], $this->wechat);
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

    public function register($code = null) {
        if($code == null) {
            $url = $this->wechat->getOauthRedirect("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '', 'snsapi_base');
            Header("Location: $url");
        }
        $access_token = $this->wechat->getOauthAccessToken();
        if($access_token) {
            $openId = $access_token['openid'];
            $user = D('User')->getUser($openId);
            if($user['register'] == 0) {
                $this->assign('openId', $openId);
                $this->display();
            } else {
                echo '用户已注册成功!';
            }
        }
    }

    public function doRegister($openId = null, $fcode = null) {
        if(!empty($fcode)) {
            $ret = D('Fcode')->useFcode($fcode);
            if(!$ret)
                $this->error('邀请码输入错误！', U('Wechat/Index/register'));
        }
        $user = D('User')->getUser($openId);
        $register = M('Register');
        $register->create();
        $register->userId = $user['id'];
        $register->installDate .= "-00";
        $register->companyDate .= "-00";
        $register->workDate .= "-00";
        $id = $register->add();
        if($id > 0) {
            $user['register'] = 1;
            D('User')->save($user);
            $this->success('提交成功！', U('Wechat/Index/register'));
        } else {
            $this->error('提交失败！');
        }
    }

    public function custom($code = null) {
        if($code == null) {
            $url = $this->wechat->getOauthRedirect("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '', 'snsapi_base');
            Header("Location: $url");
        }
        $access_token = $this->wechat->getOauthAccessToken();
        if($access_token) {
            $openId = $access_token['openid'];
            $this->assign('openId', $openId);
            $this->display();
        }
    }

    public function doCustom($openId = null) {
        $user = D('User')->getUser($openId);
        $project = M('Project');
        $project->create();
        $project->userId = $user['id'];
        $project->finishDate .= "-00";
        $project->ctime = time();
        $id = $project->add();
        if($id > 0) {
            $this->success('提交成功！', U('Wechat/Index/custom'));
        } else {

            $this->error('提交失败！');
        }
    }

    public function cooperation($code = null) {
        if($code == null) {
            $url = $this->wechat->getOauthRedirect("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '', 'snsapi_base');
            Header("Location: $url");
        }
        $access_token = $this->wechat->getOauthAccessToken();
        if($access_token) {
            $openId = $access_token['openid'];
            $this->assign('openId', $openId);
            $this->display();
        }
    }

    public function doCooperation($openId = null) {
        $user = D('User')->getUser($openId);
        $cooperation = M('Cooperation');
        $cooperation->create();
        $cooperation->userId = $user['id'];
        $cooperation->ctime = time();
        $id = $cooperation->add();
        if($id > 0) {
            $this->success('提交成功！', U('Wechat/Index/cooperation'));
        } else {

            $this->error('提交失败！');
        }
    }
}
