<?php

namespace Midori
{

	class Registry 
	{
		
		private $fields = null;
		
		private static $instance = null;
		
		private function __construct()
		{
			$this->fields = new Hash();	
		}
		
		private static function init()
		{
			if(self::$instance == null)
				self::$instance = new Registry();
			return self::$instance;
		}
		
		
		
		public static function get($property)
		{
			$self = self::init();
			return $self->fields[$property];
		}
		
		public static function set($property, $value)
		{
			$self =  static::init();
			$self->fields[$property] = $value;		
		}
				
	}

}