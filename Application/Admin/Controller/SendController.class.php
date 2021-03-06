<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class SendController extends Controller {
    public function send($type, $title, $schedule, $remark, $url) {
        $data['groupId'] = $type;
        $data['first'] = $title;
        $data['schedule'] = $schedule;
        $data['remark'] = $remark;
        $data['url'] = $url;
        $data['ctime'] = time();
        M('Sends')->data($data)->add();
        $users = D('Wechat/Register')->where('type='.$type)->select();
        foreach($users as $user) {
            A('Wechat/Index')->sendTemplate(
                $user['openId'],
                $url,
                $title,
                $schedule,
                $remark
            );
        }
        $this->success('发送成功');
    }
}
