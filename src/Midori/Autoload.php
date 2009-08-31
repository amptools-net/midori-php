<?php 

namespace Midori
{
	
	$path = get_include_path();
	$folder = __DIR__."/../";
	$real = realpath($folder);
	
	if(strpos($path, $folder) === false && strpos($path, $real) === false)
	{
		set_include_path($real.PATH_SEPARATOR.$path);		
	}
		
	

	class AutoLoad
	{
			private static $loaded = array();
	
			public static function load($class)
			{
				 $char = DIRECTORY_SEPARATOR;
				 $file = str_replace(array('_', "\\") , array($char, $char), $class) . '.php';	
				 
				self::loadFile($file);
			}
			
	
			public static function loadFile($file)
			{
				if(is_dir($file))
					return;
				if(!in_array($file, self::$loaded))
				{	
					$test = @include($file);
					 if($test === 1)
						 	self::$loaded[] = $file;
					 else {
					 	if(defined("MIDORI_ENV") && MIDORI_ENV != "production")
						{
						 	trigger_error("could not load file $file", E_USER_NOTICE);	
						}
					 }
				}	
			}
			
	}
	
	
	spl_autoload_register("Midori\AutoLoad::load");
		
}