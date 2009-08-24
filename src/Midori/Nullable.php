<?php

namespace Midori
{

	/**
	 * the abstract class which represents a nullable box type.
	 * 
	 * <pre class="brush: php">
	 *  $obj = new Midori_Int32(null);
	 *  echo $obj; // output will be 0;
	 *  if(!$obj->hasValue)
	 *  	echo "I am null!";
	 * </pre>
	 * 
	 * @author Michael
	 * @package Midori
	 * @see get_class()
	 * @see function_exists()
	 * @property-read boolean $hasValue Gets whether or not the value is not null.
	 * @property-read boolean $isNull
	 * @property-read string $nullReplacementValue; Get the defaulted value for __toString when the value is null.
	 */
	abstract class Nullable 
		extends Object implements IValueType
	{
		
		/**
		 * gets the default value that will replace the value 
		 * when __toString() is called. 
		 * 
		 * @ignore
		 * @return string
		 */
		protected function getNullReplacementValue()
		{
			return "";
		}
		
		/**
		 * gets the value 
		 * 
		 * @ignore
		 * @return mixed
		 */
		protected function getValue()
		{
			return $this->get("value");
		}
		
		/**
		 * sets the value
		 * 
		 * @ignore
		 * @param mixed $value
		 * @return void
		 */
		protected function setValue($value)
		{
			$this->set("value", $value);
		}
		
		/**
		 * gets if the object has a value.
		 * 
		 * @ignore
		 * @return boolean
		 */
		protected function getHasValue()
		{
			return ($this->value != null);
		}
		
		public function getIsNull()
		{
			return ($this->value == null);
		}
		
		public function inspect()
		{
			return $this->__toString();
		}
		
		/**
		 * 
		 * 
		 * 
		 */
		public function __toString()
		{
			if($this->hasValue)
				return "$this->value";
			return $this->nullReplacementValue;
		}
		
		protected function unbox($value)
		{
			if($value instanceof Midori_Nullable)
				return $value->value;
			return $value;	
		}
		
	}
}
