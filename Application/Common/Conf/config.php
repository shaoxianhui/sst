<?php
return array(
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => 'sst', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => 'cuso45h2o', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'sst_', // 数据库表前缀
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_PARAMS' => array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),
);
