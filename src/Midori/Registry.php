<?php

namespace Midori
{

	class Registry 
	{
		
		private static $fields = null;
		
		private static function init()
		{
			if(self::$fields == null);
				self::$fields = new Hash();		
		}
		
		public function callStatic($method, $args)
		{
			return self::$fields["Midori\\".$method];
		}
		
		public static function get($property)
		{
			self::init();
			return self::$fields[$property];
		}
		
		public static function set($property, $value)
		{
			self::init();
			return self::$fields[$property] = $value;		
		}
				
	}

}