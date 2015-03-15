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
            $this->wechat->text(D('Text')->getText(1))->reply();
            break;
        case \Org\Wechat_PHP\Wechat::MSGTYPE_EVENT:
            $event = $this->wechat->getRevEvent();
            if($event !== false) {
                switch(strtolower($event['event'])) {
                case 'subscribe':
                    $info = $this->wechat->getUserInfo($this->wechat->getRevFrom());
                    $add_or_update = D('User')->addUser($this->wechat->getRevFrom(), $info['nickname'], $this->wechat->getRevSceneId());
		    $this->wechat->news(D('News')->getNews(array(23,24,25,26)))->reply();
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
            $this->assign('openId', $openId);
            $this->display();
        }
    }

    public function doRegister($openId = null, $fcode = null, $name, $phone) {
        if(!isset($_POST) || count($_POST) == 0) 
        {
            $this->error('提交失败！');
        }
        if(!empty($fcode)) {
            $ret = D('Fcode')->useFcode($fcode);
            if(!$ret)
                $this->error('邀请码输入错误！');
        }
        $register = M('Register');
        $data = $register->create();
        if($data == false) return;
        $register->openId = $openId;
        $register->installDate .= "-00";
        $register->companyDate .= "-00";
        $register->workDate .= "-00";
        $id = $register->add();
        if($id > 0) {
            A('Register')->publish('用户姓名：'.$name.' 手机号：'.$phone.'已注册！');
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
        if(!isset($_POST) || count($_POST) == 0) 
        {
            $this->error('提交失败！');
        }
        $project = M('Project');
        $data = $project->create();
        if($data == false) return;
        $project->openId = $openId;
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
        if(!isset($_POST) || count($_POST) == 0) 
        {
            $this->error('提交失败！');
        }
        $cooperation = M('Cooperation');
        $data = $cooperation->create();
        if($data == false) return;
        $cooperation->openId = $openId;
        $cooperation->ctime = time();
        $id = $cooperation->add();
        if($id > 0) {
            $this->success('提交成功！', U('Wechat/Index/cooperation'));
        } else {

            $this->error('提交失败！');
        }
    }

    public function doQuestion() {
        if(!isset($_POST) || count($_POST) == 0) 
        {
            $this->error('提交失败！');
        }
        $question = M('Question');
        $question->create();
        $question->ctime = time();
        $id = $question->add();
        if($id > 0) {
            $this->success('提交成功！', U('Wechat/Index/question'));
        } else {

            $this->error('提交失败！');
        }
    }

    public function calc($type, $scan, $dot_page, $width, $hight) {
        $paras = "$type $scan $dot_page $width $hight";
        exec('/var/www/xinlong/Script/calc '.$paras, $res);
        $result['res'] = implode("</br>", $res);
        $result['success'] = true;
        $this->ajaxReturn($result);
    }
}
