<?php 
namespace Midori
{
	/**
	 * 
	 * @author Michael
	 * @package Midori
	 */
	class ListOf extends Enumerable
	{
		
		
		public function add()
		{
			$items = func_get_args();
			$this->addRange($items);
			return $this;
		}
		
		public function addRange($items)
		{
			foreach($items as $value)
				$this->items[] = $value;
			return $this;
		}
		
		public function clear()
		{
			$this->items = array();
			return $this;
		}
		
		public function insert()
		{
			$items = func_get_args();
			$index = array_shift($items);
			return $this->insertRange($index, $items);
		}
		
		public function insertRange($index, $items)
		{
			array_splice($this->items, $index, 0, $items);
			return $this;
		}
		
		public function remove()
		{
			$items = func_get_args();
			return $this->removeRange($items);
		}
		
		public function removeAt($index)
		{
			array_splice($this->items, $index, 1);
			return $this;
		}
		
		public function removeRange($items)
		{
			foreach($items as $item)
			{
				$index = $this->indexOf($item);
				if($index > -1)
					array_splice($this->items, $index, 1);	
			}
			return $this;
		}
		
	}
}