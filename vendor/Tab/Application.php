<?php 

namespace Tab 
{
	
	class Application extends Object implements \ArrayAccess 
	{
		private $_originalDirectory = null;
		private $_loaders = array();
		private $_appName = null;
		private $_tasks = array();
		private $lastDescription = "default";
		
		public function __construct()
		{
			$this->_originalDirectory = getcwd();
			$this->addLoader("php", new DefaultLoader());
			$this->addLoader("pake", new DefaultLoader());
			$this->addLoader("tab", new DefaultLoader());
		}
		
		protected function setAppName($name)
		{
			$this->_appName;		
		}
		
		protected function getTasks()
		{
			return $this->_tasks;		
		}
		
		public function offsetExists( $offset )
        {
            return isset( $this->$offset );
        }

        public function offsetSet($offset, $value)
        {
            $this->$offset = $value;
        }

        public function offsetGet( $taskName )
        {
            return $this->_tasks[$taskName];
        }
        
        public function defineTask($taskClass, $tasks, $args, $block)
	    {
	    	global $tab_last_description;
	    
	    	$tasks_is_array = is_array($tasks);
	    	
		    $name = $tasks_is_array ? array_shift($tasks) : $tasks;
	  		$task = new $taskClass($name, $tasks_is_array? $tasks: array(), $block, $this);
	 		$task->argumentNames = $args;
	  		if($tab_last_description != $this->lastDescription)
		  	{
	  			$task->description = $tab_last_description;
	  			$this->lastDescription = $tab_last_description;
  			}
  			$this->_tasks[$name] = $task;
	    }

        public function offsetUnset( $offset )
        {
            unset( $this->$offset );
        }
		
		protected function init($appName = "tab")
		{
			$this->appName = $appName;	
			$this->loadTabFiles();	
			
		}
		
		protected function loadTabFiles()
		{
			$files = FileUtils::getFilesForDirectory($this->_originalDirectory);
			
			foreach($files as $file)
			{
				$info = pathinfo($file);
				$ext = isset($info['extension']) ? $info['extension'] : "dir";
			
				if(isset($this->_loaders[$ext]))
					$this->_loaders[$ext]->load($file);
			}
				
		}
		
		protected function parseArgs($args)
		{
			$arguments = array();
			foreach($args as $value)
			{
				$parse = explode("=", $value);
				if(count($parse) == 2)
					$arguments[$parse[0]] = str_replace("\"","", $parse[1]);
				else 
					$arguments[] = $parse[0];
			}		
			return $arguments;
		}
		
		
		public function addLoader($ext, DefaultLoader $loader)
		{
			$this->_loaders[$ext] = $loader;			
		}
		
		public function run()
		{
			global $argv;
			require "Tasks.php";
			// shift to get rid of the first argument, the name of the script.
			array_shift($argv);
			$taskName = array_shift($argv);
			$args = $this->parseArgs($argv);
			$this->init();
			
			if($taskName && isset($this->_tasks[$taskName]))
			{
				$task = $this->_tasks[$taskName];
				$task->invoke($args);	
			} else if($taskName == false)	
				echo "no task specified";
			else {
				echo "could not find task $taskName";
			}
		}
		
		
	}		
		
}