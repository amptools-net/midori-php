<?php 


namespace Midori\Zend
{
	
	class Controller extends \Zend_Controller_Action
	{
		
		public function initView()
		{
			
			return Midori\Zend\View::get();
		}	
		
	}		
		
}