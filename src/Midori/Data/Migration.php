<?php

namespace Midori\Data
{	
	/**
	 *
	 * @Package Midori 
	 */
	abstract class Migration 
	{
		private $fields = array();
		
		public abstract function up();
		
		public abstract function down();
		
		public function __get($property)
		{
			if(isset($this->fields[$property]))
				return $this->fields[$property];
			return null;
		}
		
		public function __set($property, $value)
		{
			$this->fields[$property] =$value;
		}
	}
}