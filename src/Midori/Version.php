<?php 

namespace Midori
{
	class Version extends Object
	{
		private static $string = null;
		
		const MAJOR = 0;
    	const MINOR = 1;
    	const TINY  = 0;


		public static function string()
		{
			if(self::$string == null)
				self::$string = arrayOf(self::MAJOR, self::MINOR, self::TINY)->join(".");
			return self::$string;		
		}
	}		
}