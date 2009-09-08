<?php

namespace Midori\ActiveRecord
{	
	class ListOf extends \Midori\ListOf
	{
		private $removal;
		
		public function __construct()
		{
			$this->removal = new Midori_List();	
		}
		
		public function removeAt($index)
		{
			$item = $this->items[$index];
			$this->removal->add($item);
			parent::removeAt($index);
		}
			
		public function updateList($items)
		{
			$this->removal->addRange($this->items);
			parent::clear();
			
			$this->removal->removeRange($items);
			$this->addRange($items);
			return $this;
		}
			
		public function save()
		{
			foreach($this->removal as $item)
				$item->delete();	
			
			foreach($this->items as $item)
				$item->save();
		}		
	}
}