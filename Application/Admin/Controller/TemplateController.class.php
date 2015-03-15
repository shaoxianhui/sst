<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
class TemplateController extends Controller {
    protected $model;
    public function index($draw, $columns, $start, $length, $order, $search) {
        $column = $order[0]['column'];
        $order_name = $columns[$column]['data'];
        $dir = $order[0]['dir'];
        $users = $this->model->get($start, $length, $order_name.' '.$dir, $search['value']);
        $data['draw'] = $draw;
        $data['recordsTotal'] = $this->model->getCount($search['value']);
        $data['recordsFiltered'] = $data['recordsTotal'];
        $data['data'] = $users;
        $this->ajaxReturn($data);
    }

    public function crud($action = 'none', $data = null, $id = null) {
        switch($action) {
        case 'edit':
            $this->model->where('id='.$id)->save($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        default:
            break;
        }
    }
}
