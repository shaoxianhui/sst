<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class RegisterController extends TemplateController {
    public function __construct()
    {
        parent::__construct();
        $this->model = D('Wechat/Register');
    }

    public function index($type, $draw, $columns, $start, $length, $order, $search) {
        $column = $order[0]['column'];
        $order_name = $columns[$column]['data'];
        $dir = $order[0]['dir'];
        $users = $this->model->getRegister($type, $start, $length, $order_name.' '.$dir, $search['value']);
        $data['draw'] = $draw;
        $data['recordsTotal'] = $this->model->getRegisterCount($type);
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $users;
        $this->ajaxReturn($data);
    }
}
