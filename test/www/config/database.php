<?php

$dbConfig = array(
	'adapter' 		=>	'Pdo_Mysql',
	'development'	=>	array(
		'host'		=>	'127.0.0.1',
		'username'	=>	'midori',
		'password'	=>	'midori',
		'dbname'	=>	'midori_development',
		'options'	=>	array()
	),
	'test'			=>	array(
		'host'		=>	'127.0.0.1',
		'username'	=>	'midori',
		'password'	=>	'midori',
		'dbname'	=>	'midori_test',
		'options'	=>	array()
	),
	'staging'		=>	array(
		'host'		=>	'127.0.0.1',
		'username'	=>	'midori',
		'password'	=>	'midori',
		'dbname'	=>	'midori_staging',
		'options'	=>	array()
	),
	'production' 	=>	array(
		'host'		=>	'127.0.0.1',
		'username'	=>	'midori',
		'password'	=>	'midori',
		'dbname'	=>	'midori_production',
		'options'	=>	array()
	)
);



?>
