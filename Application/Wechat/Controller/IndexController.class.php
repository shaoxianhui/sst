<?php
namespace Wechat\Controller;
use Think\Controller;
use Think\Log;
class IndexController extends Controller {
    private $wechat;
    private $group;
	public function __construct() {
        parent::__construct();
        $this->wechat = new \Org\Wechat_PHP\TPWechat(C('WECHAT'));
        $this->group = array(
            "1" => 106,
            "2" => 105,
            "3" => 104,
            "4" => 103,
            "5" => 102,
            "6" => 101,
            "7" => 100
        );
    }
    public function index() {
		$this->wechat->valid();
        $type = $this->wechat->getRev()->getRevType();
        switch($type) {
        case \Org\Wechat_PHP\Wechat::MSGTYPE_TEXT:           
            if(preg_match('/^led:(.*)/i', $this->wechat->getRevContent(), $m)) {
                if(count($m) == 2)
                    pushText($m[1]);
            }
            D('TextMessage')->addTextMessage($this->wechat->getRevFrom(), $this->wechat->getRevContent(), $this->wechat->getRevCtime());
            $this->wechat->news(D('News')->getNews(array(27)))->reply();
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
            $this->wechat->news(D('News')->getNews(array(27)))->reply();
			break;
        }
    }

    public function updateMenu($menus = null) {
        if($menus == null) {
            $menus = D('Menu')->getMenus();
        }
        $result = $this->wechat->createMenu($menus);
        //echo $result;
    }

    public function register($code = null) {
        if($code == null) {
            $url = $this->wechat->getOauthRedirect("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '', 'snsapi_base');
            Header("Location: $url");
        }
        $access_token = $this->wechat->getOauthAccessToken();
        if($access_token) {
            $openId = $access_token['openid'];
            $map['openId'] = $openId;
            $register = M('Register')->where($map)->order('id desc')->find();
            if($register != null) {
                $register['installDate'] = substr($register['installDate'], 0, -3);
                $register['companyDate'] = substr($register['companyDate'], 0, -3);
                $register['workDate'] = substr($register['workDate'], 0, -3);
                $this->assign('register', $register);
            }
            $this->assign('openId', $openId);
            $this->display();
        }
    }

    public function doRegister($openId = null, $fcode = null, $name, $phone) {
        if(!isset($_POST) || count($_POST) == 0) {
            $this->error('提交失败！');
        }
        $map['openId'] = $openId;
        $register = M('Register');
        $id = $register->where($map)->order('id desc')->find();
        if($id != null) {
            $_POST['installDate'] = $_POST['installDate'] == '' ? '2000-01-01' : $_POST['installDate']."-01";
            $_POST['companyDate'] = $_POST['companyDate'] == '' ? '2000-01-01' : $_POST['companyDate']."-01";
            $_POST['workDate'] = $_POST['workDate'] == '' ? '2000-01-01' : $_POST['workDate']."-01";
            $register->where('id='.$id['id'])->save($_POST);
            if($_POST['type'] > 3){
                A('Register')->publish(D('Register')->toString($_POST));
            }
            $this->wechat->updateGroupMembers($this->group[$_POST['type']], $openId);
            $this->redirect('Wechat/Index/ok');
        } else {
            if(isset($_POST['update']) && $_POST['update'] == 1) {
                $this->error('您的注册信息已被删除，无法更新，请重新注册！', U('Wechat/Index/register'));
                return;
            }
            if(!empty($fcode)) {
                $belongto = D('Fcode')->useFcode($fcode);
                if(!$belongto)
                    $this->error('邀请码输入错误！');
            }
            $register = M('Register');
            $data = $register->create();
            if($data == false) return;
            $register->openId = $openId;
            $register->installDate = $_POST['installDate'] == '' ? '2000-01-01' : $_POST['installDate']."-01";
            $register->companyDate = $_POST['companyDate'] == '' ? '2000-01-01' : $_POST['companyDate']."-01";
            $register->workDate = $_POST['workDate'] == '' ? '2000-01-01' : $_POST['workDate']."-01";
            $register->belongto = isset($belongto) ? $belongto : '';
            $id = $register->add();
            if($id > 0) {
                if($_POST['type'] > 3){
                    A('Register')->publish(D('Register')->toString($_POST));
                }
                $this->wechat->updateGroupMembers($this->group[$_POST['type']], $openId);
                $this->redirect('Wechat/Index/ok');
            } else {
                $this->error('提交失败！');
            }
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
        if(!isset($_POST) || count($_POST) == 0) {
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
            A('Custom')->publish(D('Project')->toString($_POST));
            $this->redirect('Wechat/Index/ok');
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
        if(!isset($_POST) || count($_POST) == 0) {
            $this->error('提交失败！');
        }
        $cooperation = M('Cooperation');
        $data = $cooperation->create();
        if($data == false) return;
        $cooperation->openId = $openId;
        $cooperation->ctime = time();
        $id = $cooperation->add();
        if($id > 0) {
            A('Cooperation')->publish(D('Cooperation')->toString($_POST));
            $this->redirect('Wechat/Index/ok');
        } else {

            $this->error('提交失败！');
        }
    }

    public function doQuestion() {
        if(!isset($_POST) || count($_POST) == 0) {
            $this->error('提交失败！');
        }
        $question = M('Question');
        $question->create();
        $question->ctime = time();
        $id = $question->add();
        if($id > 0) {
            A('Question')->publish(D('Question')->toString($_POST));
            $this->redirect('Wechat/Index/ok');
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

    public function order($code = null) {
        if($code == null) {
            $url = $this->wechat->getOauthRedirect("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '', 'snsapi_base');
            Header("Location: $url");
        }
        $access_token = $this->wechat->getOauthAccessToken();
        if($access_token) {
            $openId = $access_token['openid'];
            $register = D('Register')->getUserRegister($openId);
            if($register == null || $register['type'] < 5 || $register['status'] == 0) {
                echo '<html lang="en"><head><meta charset="utf-8"></head><h1><font color="#FF0000">在线下单功能只针对经销商开放，如果您为鈊龙卡经销商请使用注册微信登录。如未注册请联系您的销售代表帮助您完成注册或注册成“申请经销商”客户，我们在收到注册信息后会第一时间联系您。感谢您对鈊龙控制卡的关注和支持！</font></h1></html>';
                return;
            }
            $products = M('Product')->where('active=1')->order('id asc')->select();
            $this->assign('products', $products);
            $this->assign('openId', $openId);
            $this->assign('type', $register['type']);
            $map['openId'] = $openId;
            $order = M('Order')->where($map)->order('id desc')->find();
            if($order != null) {
                $this->assign('name', $order['name']);
                $this->assign('location', $order['location']);
                $this->assign('phone', $order['phone']);
            } else {
                $this->assign('name', $register['name']);
                $this->assign('phone', $register['phone']);
            }
            $this->display();
        }
    }

    public function doOrder($openId = null) {
        if(!isset($_POST) || count($_POST) == 0) {
            $this->error('提交失败！');
        }
        $register = D('Register')->getUserRegister($openId);
        $order['openId'] = $openId;
        $order['amount'] = $_POST['amount'];
        $order['name'] = $_POST['name'];
        $order['phone'] = $_POST['phone'];
        $order['location'] = $_POST['location'];
        $order['company'] = $register['company'];
        $_POST['company'] = $register['company'];
        $order['code'] = date('Ymd').substr(microtime(), 4, 4);
        $order['ctime'] = time();
        $id = M('Order')->data($order)->add();
        if($id <= 0) {
            $this->error('提交失败！');
        }
        $items = array();
        $str = '';
        foreach($_POST as $key => $value) {
            if(is_numeric($key) && $value > 0) {
                $item['orderId'] = $id;
                $item['productId'] = $key;
                $product = M('Product')->find($key);
                $item['productName'] = $product['name'];
                $item['productPrice'] = $_POST['price'.$key];
                $item['quantity'] = $value;
                $str .= $item['productName'] . ' ' . $item['quantity'] . '个 单价 ' .$item['productPrice']. "元\n";
                array_push($items, $item);
            }
        }
        $ret = M('OrderItem')->addAll($items);
        if($ret > 0) {
            A('Order')->publish(D('Order')->toString($_POST) . $str);
            $this->success('提交成功！', U('Wechat/Index/order_success', array('itemId' => $ret)));
        } else {

            $this->error('提交失败！');
        }
    }

    public function myorder($code = null) {
        if($code == null) {
            $url = $this->wechat->getOauthRedirect("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '', 'snsapi_base');
            Header("Location: $url");
        }
        $access_token = $this->wechat->getOauthAccessToken();
        if($access_token) {
            $openId = $access_token['openid'];
            $where['openId'] = $openId;
            $orders = D('Order')->relation(true)->where($where)->order('id desc')->limit(5)->select();
            $this->assign('orders', $orders);
            $this->display();
        }
    }

    public function order_success($itemId = null) {
        if($itemId == null)
            return;
        $item = M('OrderItem')->find($itemId);
        $order = D('Order')->relation(true)->find($item['orderId']);
        $this->assign('order', $order);
        $this->display();
    }

    public function sendTemplate($openId, $url, $first, $schedule, $remark) {
        if($openId == null) {
            return;
        }
        $message['touser'] = $openId;
        $message['template_id'] = '2CDsLqd9Tx3tUMutboAH0PS2pLRQBCrcpCesnuCxFa4';
        $message['url'] = $url;
        $message['topcolor'] = '#00FF00';
        $message['data']['first'] = array('value' => $first, 'color' => '#000000');
        $message['data']['schedule'] = array('value' => $schedule, 'color' => '#000000');
        $message['data']['time'] = array('value' => date('Y-m-d'), 'color' => '#000000');
        $message['data']['remark'] = array('value' => $remark, 'color' => '#FF8715');
        $this->wechat->sendTemplateMessage($message);
    }

    public function updateGroupMembers($openId, $type) {
        $this->wechat->updateGroupMembers($this->group[$type], $openId);
    }
}
