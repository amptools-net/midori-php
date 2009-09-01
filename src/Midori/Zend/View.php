<?php

namespace Midori\Zend
{
	class View extends \Zend_View
	{
	    private static $instance;
	    private $fields = array();
	    
	    public static function get()
	    {
	        if(self::$instance == null)
		    {
		    	$config = \Midori\config();
	            self::$instance = new View();
	            self::$instance->fields = new Params();
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
	        $this->setHelperPath($path . 'helpers', $classPrefix . '');
	        $this->setFilterPath($path . 'filters', $classPrefix . '');
	        return $this;
	    }
	    
	    public function setVars($variables)
		{
			$this->fields = new Params($variables);  	
		}
		
		public function __get($property)
		{
			return $this->fields[$property];
		}
	    
	    
	   /**
	     * Includes the view script in a scope with only public $this variables.
	     *
	     * @param string The view script to execute.
	     */
	    protected function _run()
	    {
	    	extract($this->fields->toArray());
	        if ($this->_useViewStream && $this->useStreamWrapper()) {
	            include 'zend.view://' . func_get_arg(0);
	        } else {
	            include func_get_arg(0);
	        }
	    }
	    
	    
	}
}