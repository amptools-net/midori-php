<?php
	
namespace Midori\ActiveRecord
{
	class ValidationList  extends \Midori\ListOf
	{
		private static $listMeta = null;
		
		public function __construct($hashable = array())
		{
			
			$args = func_get_args();
	
			if(count($args) == 1 && (is_array($args[0]) || $args[0] instanceof \IteratorAggregate))
				$args = $args[0];
	
			foreach($args as $item)
				$this->items[] = $item;
			
		} 
		
	
		
		public function getValidations($propertyName)
		{
			$list = new \Midori\ListOf();
			foreach($this->items as $value)
				if(strtolower($value->propertyName) == strtolower($propertyName))
					$list->add($value);
			return $list;
		}
	}
}
	
