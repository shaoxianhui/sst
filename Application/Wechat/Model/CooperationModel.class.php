<?php
namespace Wechat\Model;
use Think\Model;
class CooperationModel extends TableModel {
    protected function filter(&$cooperation) {
        for($i = 0; $i < count($cooperation); $i++) {
            $cooperation[$i]['pr_ci_co'] = $cooperation[$i]['pr'].$cooperation[$i]['ci'].$cooperation[$i]['co'];
        }
    }
}
