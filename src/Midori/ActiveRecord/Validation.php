<?php

namespace Midori\ActiveRecord
{

	/**
	 * 
	 * @author Michael
	 * @package midori
	 * @property string $propertyName
	 * @property string $message
	 */
	abstract class Validation extends \Midori\Data\Object
	{
		private $repo = array();
		
		private $if;
		
		public function getErrorMessage()
		{
			$string = new \Midori\String($this->propertyName);
			
			return sprintf($this->message, $string->titleize(true));
		}
		
		public function getjQueryRule($value, $object)
		{
			return null;		
		}
		
		public function validate($value, $object)
		{
			$class = __CLASS__;
			throw new \Midori\Exception("validate was not implemented in $class");
		}
		
		public function __toString()
		{
			return get_class($this);
		}
	}
}