<?php

namespace Midori\ActiveRecord
{
	
	class ValidateFormat extends Validation
	{
		
		public function __construct($propertyName, $format, $message, $if = null)
		{
			$this->propertyName = $propertyName;
			$this->format = $format;
			$this->message = $message;
			$this->if = $if;
		}
		
		public function getjQueryRule($value, $object)
		{
			$proc = $this->if;
			if($proc && call_user_func_array($proc, array($value, $object)))
				return null;
			return array("regex: {$this->format}", "regex: \"{$this->getErrorMessage()}\"");
		}
		
		public function validate($value, $object)
		{
			$proc = $this->if;
			if($proc && call_user_func_array($proc, array($value, $object)))
				return true;
			if(!is_null($value) && $value != '' )
				return preg_match($this->format, $value);
			return true;
		}
		
	}
}