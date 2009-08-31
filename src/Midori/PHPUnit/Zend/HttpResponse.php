<?php

namespace Midori\PHPUnit\Zend

	class HttpResponse extends \Zend_Controller_Response_HttpTestCase
	{
		
		
		public function getResponse()
		{
			$headers = $this->sendHeaders();
	        $content = implode("\n", $headers) . "\n\n";
	
	        if ($this->isException() && $this->renderExceptions()) {
	            $exceptions = '';
	            foreach ($this->getException() as $e) {
	                $exceptions .= $e->__toString() . "\n";
	            }
	            $content .= $exceptions;
	        } else {
	            $content .= $this->outputBody();
	        }
	
	        return $content;
		}
		
		public function sendResponse()
		{
			return null;
		}
		
	}
}