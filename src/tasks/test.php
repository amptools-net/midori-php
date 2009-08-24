<?php



desc("start selenium");
task("selenium:run", null, function($argv){
	
	$path = ROOT."vendor/Selenium-Server/selenium-server.jar";
	system("java -jar {$path}");
});



desc("test one test");
task("test", null, function($argv){
	
	Tab\Tab::addIncludePaths(
		ROOT."test/",
		ROOT."test/Midori"
	);
		
	require "tests-bootstrap.php";
	
	$argv = $argv->args;
	if(count($argv) == 1)
	{
	 	$args = array("Single Test");
	 	foreach($argv as $item)
	 		$args[] = $item;
	}
	
	$_SERVER['argv'] = $args;
	
	require_once 'PHPUnit/Util/Filter.php';
	require_once 'Midori/PHPUnit/Spec.php';
	
	
		
	PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');
	
	require 'PHPUnit/TextUI/Command.php';
	
	define('PHPUnit_MAIN_METHOD', 'PHPUnit_TextUI_Command::main');
	
	PHPUnit_TextUI_Command::main();
});