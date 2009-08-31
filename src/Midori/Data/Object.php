<?php

namespace Midori\Data
{
		
	class Object
	{
		protected $attributes = array();
		
		public function __get($property)
		{
			if(isset($this->attributes[$property]))
				return $this->attributes[$property];
			return null;
		}
		
		public function __set($property, $value)
		{
			$this->attributes[$property] = $value;
		}
		
		
	}
}