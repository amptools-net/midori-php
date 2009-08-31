<?php 
error_reporting(E_ALL);
if(!defined("MIDORI_ROOT"))
	define("MIDORI_ROOT", realpath(dirname(__FILE__)."/../")."/");

if(!defined("MIDORI_SCRIPT"))
	define("MIDORI_SCRIPT", false);
	
if(!defined("MIDORI_ENV"))
{
	if(file_exists(MIDORI_ROOT."/config/.config"))
	{
		require ".config";	
	}
	else 
		define("MIDORI_ENV", "development");	
}
	
	function vendor()
	{
		return is_dir(MIDORI_ROOT."vendor/");		
	}
	
	function config()
	{
		return Midori\Vars::get("Midori/config");		
	}
	
	
	function env()
	{
		return MIDORI_ENV;		
	}
	
	function root()
	{
		return MIDORI_ROOT;		
	}	
	

if(vendor())
{
	require_once  MIDORI_ROOT."vendor/Midori/Util.php";
	require_once  MIDORI_ROOT."vendor/Midori/Application/Boot.php";
	require_once  MIDORI_ROOT."vendor/Midori/Zend/Application/Boot.php";
} 


Midori\boot(MIDORI_SCRIPT === false);