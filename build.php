<?php 


$dir = realpath(dirname(__FILE__));

if(!defined("ROOT"))
	define("ROOT", $dir."/");
if(!defined("MIDORI_SCRIPT"))
	define("MIDORI_SCRIPT", true);
	
set_include_path( 
	ROOT."src/Midori/". PATH_SEPARATOR.
	ROOT."vendor/".PATH_SEPARATOR.
	ROOT."test/Midori"
);


@include "Midori/Tab/tasks.php";





