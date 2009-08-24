<?php 

namespace Tab {
	
	require_once "TaskArgs.php";
	
	class Task extends Object implements \ArrayAccess
	{
		private $_application;
		
		private $_name = "";
		private $_description = "";
		private $_closure = null;
		private $_argumentNames = array();
		private $_prerequisites = null;
		private $_called = false;
		
		public function __construct($name, $required, $proc,  $app)
		{
			$this->_name = $name;
			$this->_prerequisites = $required ?: array();
			$this->_closure = $proc;
			$this->_application = $app;
		}
		
		protected function getName()
		{
			return $this->_name;		
		}
		
		protected function getDescription()
		{
				return $this->_description;
		}
		
		protected function setDescription($description)
		{
			$this->_description = $description;		
		}
		
		protected function setArgumentNames($names)
		{
			$names = $names ?: array();
			foreach($names as $key => $value)
			{
				if(is_numeric($key))
					$this->_argumentNames[$value] = $value;
				else 
					$this->_argumentNames[$key] = $value;
			}
		}
		
		protected function getArgumentNames()
		{
			return $this->_argumentNames;		
		}
		
		public function offsetExists( $offset )
        {
            
        }

        public function offsetSet($offset, $value)
        {
            
        }

        public function offsetGet( $taskName )
        {
            return $this->_application[$taskName];
        }

        public function offsetUnset( $offset )
        {
           
        }
		
        public function call($argv = array())
	    {
	    	$args = new TaskArgs($this->argumentNames, $argv);
    		$this->_called = false;
        	$this->invokeChain($args);    	
	    }
		
		public function invoke($argv = array())
		{
			$args = new TaskArgs($this->argumentNames, $argv);
			$this->invokeChain($args);
		}
		
		protected function invokeChain($args)
		{
			if(!$this->_called)
			{
				$this->_called = true;
				foreach($this->_prerequisites as $taskName)
				{
					$task = $this->_application[$taskName];
					$task->invoke($args->args);
				}
				$method = $this->_closure;
				$method($args);
			}		
		}
	}
	
}

