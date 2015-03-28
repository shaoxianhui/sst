<?php
namespace Wechat\Model;
use Think\Model;
class RegisterModel extends TableModel {
    public $map = array('name' => array('name' => '名字'),
                        'type' => array('name' => '注册类型', '1' => '普通用户', '2' => '销售人员', '3' => '申请经销商', '4' => '经销商', '5' => '普通用户', '6' => '代理商', '7' => '特邀客户'),
                        'sex' => array('name' => '性别', '1' => '男', '0' => '女'),
                        'telephone' => array('name' => '联系电话'),
                        'phone' => array('name' => '手机'),
                        'qq' => array('name' => 'QQ'),
                        'email' => array('name' => '邮箱'),
                        'pr' => array('name' => '省'),
                        'ci' => array('name' => '市'),
                        'co' => array('name' => '县'),
                        'scene' => array('name' => '显示屏应用环境', '1' => '个人', '2' => '公司', '3' => '医院', '4' => '政府'),
                        'installDate' => array('name' => '安装时间'),
                        'updateCycle' => array('name' => '显示屏数据更新周期', '0' => '未选择', '1' => '年', '2' => '月', '3' => '星期', '4' => '日'),
                        'updateType' => array('name' => '显示屏数据更新方式', '0' => '未选择', '1' => '固定电脑', '2' => '临时电脑', '3' => '笔记本', '4' => '手机', '5' => '其他人帮助更新'),
                        'atCompany' => array('name' => '所属公司或个人'),
                        'workDate' => array('name' => '工作起始时间'),
                        'brands' => array('name' => '常用控制卡品牌'),
                        'evaluation' => array('name' => '鈊龙卡评价'),
                        'company' => array('name' => '公司名称'),
                        'companyLoaction' => array('name' => '公司地址'),
                        'companyDate' => array('name' => '公司成立时间'),
                        'sales' => array('name' => '控制卡年销售额')
    );
    public function __construct()
    {
        parent::__construct();
        $this->search_column = 'name,phone,telephone';
    }

    protected function filter(&$register) {
        for($i = 0; $i < count($register); $i++) {
            $register[$i]['installDate'] = substr($register[$i]['installDate'], 0, -3);
            $register[$i]['companyDate'] = substr($register[$i]['companyDate'], 0, -3);
            $register[$i]['workDate'] = substr($register[$i]['workDate'], 0, -3);
        }
    }

    public function getRegister($type, $start = 0, $length = 10, $order = 'ctime desc', $search = null) {
        $page = $start / $length + 1;
        $map['type'] = $type;

        if($search != null) {
            $cs = explode(',', $this->search_column);
            $fi['_logic'] = 'or';
            foreach($cs as $c) {
                $fi[$c] = array('like', "%$search%");
            }
            $map['_complex'] = $fi;
            $rows = $this->where($map)->order($order)->page($page.','.$length)->select();
        } else {
            $rows = $this->where($map)->order($order)->page($page.','.$length)->select();
        }
        if($rows === null)
            return array();
        $this->filter($rows);
        return $rows;
    }

    public function getRegisterCount($type, $search) {
        $map['type'] = $type;
        if($search != null) {
            $cs = explode(',', $this->search_column);
            $fi['_logic'] = 'or';
            foreach($cs as $c) {
                $fi[$c] = array('like', "%$search%");
            }
            $map['_complex'] = $fi;
        }
        return $this->where($map)->count();
    }

    public function getUserRegister($openId) {
        $map['openId'] = $openId;
        return $this->where($map)->find();
    }

    public function callback_delete($id) {
        $register = $this->find($id);
        A('Wechat/Index')->sendTemplate(
            $register['openId'],
            '',
            '注册信息删除通知',
            '注册信息删除',
            '您的注册信息不完整无法通过审核，请重新注册谢谢！'
        );
    }

    public function callback_edit($id, &$data) {
        $data['installDate'] .= '-01';
        $data['companyDate'] .= '-01';
        $data['workDate'] .= '-01';
        $register = $this->find($id);
        if($register['status'] == 0 && $data['status'] == 1)
        {
            A('Wechat/Index')->sendTemplate(
                $register['openId'],
                '',
                '注册审核通知',
                '审核通过',
                '您的注册申请已经通过，“在线下单”、“订单查询”功能可以使用。'
            );
        }
    }
}
