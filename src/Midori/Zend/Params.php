<?php 

namespace Midori\Zend
{
		
	class Params extends \Midori\Hash
	{
		public function offsetGet($key)
		{
			$value = parent::offsetGet($key);	
			if(is_array($value))
			{ 
				return new Hash($value);
			}
			return $value;
		}
	}		
}