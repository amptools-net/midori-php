<?php

namespace Midori
{
	
	class Console extends Object
	{
		
		public static function write()
		{
			$args = func_get_args();
			fwrite(STDOUT, sprintf(array_shift($args), $args));
		}
	
		public static function writeLine()
		{
			$args = func_get_args();
			fwrite(STDOUT, sprintf("\n". array_shift($args), $args));
		}
		
		public static function consoleError($number, $string, $file, $line)
		{
		 	fwrite(STDERR,"\n [error] $string in $file on $line\n"); 
		}
		
		public static function read()
		{
			return trim(fgets(STDIN));		
		}
	}
	
}