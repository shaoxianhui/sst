<?php
namespace Wechat\Model;
use Think\Model;
class UserModel extends Model {

    public function getUser($openId) {
        $where['openId'] = $openId;
        return $this->where($where)->find();
    }

    public function getUsers($start = 0, $length = 10, $order = 'id asc') {
        $page = $start / $length + 1;
        return $this->order($order)->page($page.','.$length)->select();
    }

    public function getCount() {
        return $this->count();
    }

    public function addUser($openId, $nickname, $qrScene) {
        $add_or_update = true;
        $user_data['openId'] = $openId;
        $user = $this->where($user_data)->find();
        if($user !== null) {
            $user['subscribe'] = 1;
            $user['nickname'] = $nickname;
            if($qrScene !== false && is_numeric($qrScene)) {
                $user['qrScene'] = $qrScene;
            } else {
                $user['qrScene'] = 0;
            }
            $this->save($user);
            $add_or_update = false;
        } else {
            $user_data['subscribe'] = 1;
            $user_data['nickname'] = $nickname;
            if($qrScene !== false && is_numeric($qrScene)) {
                $user_data['qrScene'] = $qrScene;
            } else {
                $user['qrScene'] = 0;
            }
            $this->data($user_data)->add();
        }
        return $add_or_update;
   }

    public function removeUser($openId) {
        $where['openId'] = $openId;
        $this->subscribe = 0;
        $this->where($where)->save();
    }
}
