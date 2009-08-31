<?php

$dbConfig = array(
	'adapter' 		=>	'Pdo_Mysql',
	'development'	=>	array(
		'host'		=>	'127.0.0.1',
		'username'	=>	'root',
		'password'	=>	'',
		'dbname'	=>	'newswise_development',
		'options'	=>	array()
	),
	'test'			=>	array(
		'host'		=>	'127.0.0.1',
		'username'	=>	'root',
		'password'	=>	'',
		'dbname'	=>	'newswise_test',
		'options'	=>	array()
	),
	'staging'		=>	array(
		'host'		=>	'127.0.0.1',
		'username'	=>	'root',
		'password'	=>	'',
		'dbname'	=>	'newswise_staging',
		'options'	=>	array()
	),
	'production' 	=>	array(
		'host'		=>	'127.0.0.1',
		'username'	=>	'nwuser',
		'password'	=>	'vdt4',
		'dbname'	=>	'newswise_production',
		'options'	=>	array()
	)
);



?>
