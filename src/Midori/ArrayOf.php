<?php 

namespace Midori
{
	/**
	 *
	 * since php is gay, it forces you to use words other than array and list
	 * because it considers them keywords.....
	 * 
	 * @author Michael
	 * @package Midori
	 */
	class ArrayOf extends Enumerable
	{
		
		public function copy()
		{
			return  clone $this;
		}
		
		public function __clone()
		{
			$obj = new ArrayOf();
			$obj->items = $this->items;
			return $obj;
		}
		
		public function clear()
		{
			$this->items = array();
			return $this;
		}
		
		public function pack($templateString)
		{
			$string = "";
			foreach($this->items as $item)
				$string .= pack($templateString, $item);
			return $string;
		}
		
		public function push()
		{
			$items = func_get_args();
			foreach($items as $item)
				$this->items[] = $item;
			return $this;
		}
		
		public function pop()
		{
			return array_pop($this->items);
		}
		
		public function shift()
		{
			return array_shift($this->items);
		}
		
		public function uniq()
		{
			$this->items = array_unique($this->items);
			return $this;
		}
		
		public function unshift()
		{
			$items = array_reverse(func_get_args());
			foreach($items as $item)
				array_unshift($this->items, $item);
			return $this;
		}
		
	}
}