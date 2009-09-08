<?php
namespace Midori\ActiveRecord
{	
	class ValidateRequired extends Validation 
	{
		public function __construct($propertyName, $message, $if = null)
		{
			$this->propertyName = $propertyName;
			
			$this->message = $message ?: '%1$s is required';
			$this->if = $if;	
		}
		
		public function getjQueryRule($value, $object)
		{
			$proc = $this->if;
		
			$result =  $proc && call_user_func_array($proc, array($value, $object));
		
			if($result)
			{
				return null;
				
			}
			return array("required: true", "required: \"{$this->getErrorMessage()}\"");		
		}
		
		public function validate($value, $object)
		{
			$proc = $this->if;
			if($proc && call_user_func_array($proc, array($value, $object)))
				return true;
			return (!is_null($value) && !box_str($value)->empty);
		}
	}
}