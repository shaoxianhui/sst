<?php
namespace Wechat\Model;
use Think\Model;
class ProjectModel extends TableModel {
    protected function filter(&$project) {
        for($i = 0; $i < count($project); $i++) {
            $project[$i]['pr_ci_co'] = $project[$i]['pr'].$project[$i]['ci'].$project[$i]['co'];
        }
    }
}
