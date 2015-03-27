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
            $this->model->callback_edit($id, $data);
            $this->model->where('id='.$id)->save($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        case 'create':
            $this->model->callback_create($data);
            $id = $this->model->add($data);
            $data['id'] = $id;
            $result['row'] = $data;
            $this->ajaxReturn($result);
            break;
        case 'remove':
            $this->model->callback_delete($id);
            $this->model->relation(true)->delete(join(',', $id));
            $result['id'] = $id;
            $this->ajaxReturn($result);
            break;
        default:
            break;
        }
    }
}
