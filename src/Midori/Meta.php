<?php

namespace Midori
{
	class Meta
	{
		private static $meta = array();
		
		
		public static function add($class, $name, $value)
		{
			if(!isset(self::$meta[$class]))
				self::$meta[$class] = array();
			self::$meta[$class][$name] = $value;
		}
		
		public static function get($class, $name)
		{
			if(!isset(self::$meta[$class]))
				self::$meta[$class] = array();
			if(!isset(self::$meta[$class][$name]))
				return null;
			return self::$meta[$class][$name];
		}
		
	}
}