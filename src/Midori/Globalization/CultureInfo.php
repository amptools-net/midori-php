<?php 

namespace Midori\Globalization
{
			
	/**
	 *
	 * 
	 * 
	 * @property string $dateFormat
	 */
	class CultureInfo extends \Midori\Object
	{
		
		private static $_currentCulture = null;
		
		protected function getEnglishDisplayName()
		{
			return $this->get("englishDisplayName");
		}
		
		protected function setEnglishDisplayName($value)
		{
			$this->set("englishDisplayName", $value);		
		}
		
		protected function getDisplayName()
		{
			return $this->get("displayName");
		}
		
		protected function setDisplayName($value)
		{
			$this->set("displayName", $value);		
		}
		
		protected function getDateFormat()
		{
			return	$this->get("dateFormat");
		}
		
		protected function setDateFormat($value)
		{
			$this->set("dateFormat", $value);		
		}
		
		public static function setCurrentCulture($name)
		{
			throw new Exception("not yet implemented");
		}
		
		public static function currentCulture()
		{
			if(self::$_currentCulture == null)
			{
				$culture = new CultureInfo();
				$culture->dateFormat = "n/j/Y g:i:s A";
				$culture->displayName = "English (us)";
				$culture->englishDisplayName ="English (us)";
				self::$_currentCulture = $culture;	
			}
			return self::$_currentCulture;		
		}
			
	}
	
}