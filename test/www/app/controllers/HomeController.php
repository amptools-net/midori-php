<?php


class HomeController extends ApplicationController
{

	public function index()
	{
		echo   "TEst";
			$this->_helper->layout()->disableLayout();
			//$this->_helper->viewRenderer->setNoRender();
	}
		
}