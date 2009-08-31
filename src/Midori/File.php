<?php

namespace Midori
{
		
	class File extends Object
	{
		
		public static function join()
		{
			return implode(DIRECTORY_SEPARATOR, func_get_args());		
		}
		
		public static function link($from, $to)
		{
			if(is_dir($from) && PHP_OS == "WINNT")
			{
				exec("mklink /J $to $from");
				return;		
			} 
			link($from, $to);
		}
		
		public static function symlink($from, $to)
		{
			symlink($from, $to);
		}
		
		
		
		public static function readAllText($path)
		{
			$fh = fopen($path, "r");
			
			$lines = "";
			
			while (!feof($fh)) {
				$lines .= fgets($fh);
			}
			
			fclose($fh);
			
			return $lines;
		}
		
		public static function writeAllText($path, $text, $overwrite = false)
		{
			$fh = fopen($path, $overwrite ? "w+" : "w");
			fwrite($fh, $text);
			fclose($fh);	
		}
		
		public static function appendAllText($path, $text)
		{
			$fh = fopen($path, "a+");
			fwrite($fh, $text);
			fclose($fh);	
		}
		
	}	
		
	
	
}