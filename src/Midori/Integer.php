<?php


namespace Midori
{
	/**
	 * 
	 * @author Michael
	 * @package Midori
	 */
	class Integer extends Nullable
	{
		
		public function times($method)
		{
			for($i = 0; $i < $this->value; $i++)
				$method($i);
		}
		
		public function __construct($value = 0)
		{
			$this->value = (int)$value;
		}
		/**
		 * 
		 * Enter description here...
		 * @param $value
		 * @return Midori_Int32
		 */
		public function __invoke($value)
		{
			return new Integer($value);
		}
	}

}

