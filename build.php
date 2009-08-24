<?php 


$dir = realpath(dirname(__FILE__));

if(!defined("ROOT"))
	define("ROOT", $dir."/");
if(!defined("MIDORI_SCRIPT"))
	define("MIDORI_SCRIPT", true);
	
set_include_path( 
	ROOT.PATH_SEPARATOR.
	ROOT."src/". PATH_SEPARATOR.
	ROOT."vendor/"
);

include "src/tasks/tasks.php";
@include "Midori/Tab/tasks.php";





