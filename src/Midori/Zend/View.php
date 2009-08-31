<?php

namespace Midori\Zend
{
	class View extends \Zend_View
	{
	    private static $instance;
	    
	    public static function get()
	    {
	        if(self::$instance == null)
		    {
		    	$config = \Midori\config();
	            self::$instance = new View();
            }
	        return self::$instance;
	    }
	    
	    
	     public function setBasePath($path, $classPrefix = '')
	    {
	        $path        = rtrim($path, '/');
	        $path        = rtrim($path, '\\');
	        $path       .= DIRECTORY_SEPARATOR;
	        $classPrefix = rtrim($classPrefix, '_') . '_';
	        $this->setScriptPath($path . 'views');
	        $this->setHelperPath($path . 'helpers', $classPrefix . 'Helper');
	        $this->setFilterPath($path . 'filters', $classPrefix . 'Filter');
	        return $this;
	    }
	    
	    
	}
}