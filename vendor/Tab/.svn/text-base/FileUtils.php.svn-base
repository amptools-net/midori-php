<?php

namespace Tab {
	
	class FileUtils
	{
			
		public static function getFilesForDirectory($directory)
		{
			$files = array();
			if ($handle = opendir($directory)) 
			{
   
			    while (false !== ($file = readdir($handle))) 
			    {
			       $files[] = $file;
			    }
			    
    			closedir($handle);
			}	
			
			return $files;
		}		
	}
	
}