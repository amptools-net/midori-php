<?php

namespace Tab {
	
	class Object 
	{
	
			
		public function __get($propertyName)
		{
			$get = "get{$propertyName}";
			return $this->$get();	
		}
		
		public function __set($propertyName, $value)
		{
			$set = "set{$propertyName}";
			$this->$set($value);
		}
	}
}