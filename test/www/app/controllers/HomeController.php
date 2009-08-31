<?php


class HomeController extends ApplicationController
{

	public function indexAction()
	{
		echo   "TEst";
			$this->_helper->layout()->disableLayout();
			//$this->_helper->viewRenderer->setNoRender();
	}
		
}