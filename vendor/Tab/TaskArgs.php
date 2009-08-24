<?php


namespace Tab
{
	class TaskArgs
	{
		
		public function __construct($names, $args)
		{
			foreach($names as $key => $value)
			{
				$this->$key = isset($args[$key]) ? $args[$key] : null;	
			}	
			$this->args = $args;
		}
		
	}		
		
}