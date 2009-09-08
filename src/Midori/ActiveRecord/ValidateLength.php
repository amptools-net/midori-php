<?php
namespace Midori\ActiveRecord
{	
	class ValidateLength extends Validation 
	{
		public function __construct($propertyName, $message = null, $min = null, $max = null, $if = null)
		{
			$this->propertyName = $propertyName;
		
			$this->if = $if;
			$this->min = $min;
			$this->max = $max;
			$this->message = $message;
			if($this->message == null)
			{
				$this->message = '%1$s should be';
				if($this->min && $this->max)
					$this->message .= ' between %2$s and %3$s characters';
				else 
				{
					$this->message .= $this->min ? ' at least %2$s%3$s characters' : "";
					$this->message .= $this->max ? ' less than %3$s%2$s characters' : "";
				}
			}
		}
		
		public function getErrorMessage()
		{
			$string = new Midori_String($this->propertyName);
			
			return sprintf($this->message, $string->titleize(true), $this->min , $this->max);
		}
		
		public function getjQueryMessage($value, $object)
		{
			
		}
		
		public function getjQueryRule($value, $object)
		{
			$proc = $this->if;
		
			
			$result =  $proc && call_user_func_array($proc, array($value, $object));
		
			if($result)
				return null;
				
			if($this->min && $this->max)
				return array("rangelength: [{$this->min}, {$this->max}]", "rangelength: \"{$this->getErrorMessage()}\"");
			if($this->min)
				return array("minlength: {$this->min}", "minlength: \"{$this->getErrorMessage()}\"");
			if($this->max)
				return array("maxlength: {$this->max}", "minlength: \"{$this->getErrorMessage()}\"");
			return null;	
		}
		
		public function validate($value, $object)
		{
			$proc = $this->if;
			if($proc && call_user_func_array($proc, array($value, $object)))
				return true;
			
			$length = box_str($value)->length;
			
			if($this->min != null && $this->min > $length)
				return false;
				
			if($this->max != null && $this->max < $length)
				return false;
			
			return true;
		}
	}
}