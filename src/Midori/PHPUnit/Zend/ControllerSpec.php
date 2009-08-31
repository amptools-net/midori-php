<?php
	
namespace Midori\PHPUnit\Zend
{
	\PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');
	
	
	if(!class_exists("Midori_PHPUnit_Zend_ControllerSpec"))
	{
	
		class Midori\PHPUnit\Zend\ControllerSpec extends \PHPUnit_Framework_TestCase
		{
			/**
		     * @var mixed Bootstrap file path or callback
		     */
		    public $bootstrap;
		    
		    protected $expectation;
		
		    /**
		     * @var Zend_Controller_Front
		     */
		    protected $_frontController;
		
		    /**
		     * @var Zend_Dom_Query
		     */
		    protected $_query;
		
		    /**
		     * @var Zend_Controller_Request_Abstract
		     */
		    protected $_request;
		    
		    /**
		     * @var Zend_Controller_Response_Abstract
		     */
		    protected $_response;
			
		   protected function fixtures()
			{
				Console::writeLine("====== Loading Fixtures for ".get_class($this)." =======");
				$start = microtime(true);
				Midori\Data\Fixtures::load(func_get_args());
				Console::writeLine("took: ".benchmark($start, microtime(true)));
				Console::writeLine("====== Loaded Fixtures for ".get_class($this)."  =======\n");
			}
			
			
		 	public function reset()
		    {
		        $_SESSION = array();
		        $_GET     = array();
		        $_POST    = array();
		        $_COOKIE  = array();
		        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		        $this->resetRequest();
		        $this->resetResponse();
		        Zend_Layout::resetMvcInstance();
		        Zend_Controller_Action_HelperBroker::resetHelpers();
		        $this->frontController->resetInstance();
		        Zend_Session::$_unitTestEnabled = true;
		    }
			
			public function __get($property)
			{
				$get = "get$property";
				return $this->$get();
			}
			
			public function setUp()
			{
				$this->reset();
				
				$this->getfrontController()
		             ->setRequest($this->getRequest())
		             ->setResponse($this->getResponse())
		              ->setResponse($this->getResponse())
		             ->throwExceptions(false)
		             ->returnResponse(true);
		             
		        \Midori\boot(false);
			}
			
			public function assertResponseCode($code, $message = '')
		    {
		        $this->_incrementAssertionCount();
		        require_once 'Zend/Test/PHPUnit/Constraint/ResponseHeader.php';
		        $constraint = new \Zend_Test_PHPUnit_Constraint_ResponseHeader();
		        $response   = $this->response;
		        if (!$constraint->evaluate($response, __FUNCTION__, $code)) {
		            $constraint->fail($response, $message);
		        }
		    }
			
			/**
			 *
			 * @return Midori_PHPUnit_Expect
			 */
			protected function expectsThat($data, $message = null)
			{
				if($this->expectation == null)
					$this->expectation = new \Midori\PHPUnit\Expect();
				
				$this->expectation->that($data, $message);
				return $this->expectation;
			}
			
			public function get($url, $params = array())
			{
				$this->getRequest()->setMethod("GET");
				$this->getRequest()->setParams($params);
				return $this->dispatch($url);
			}
		
			public function delete($url, $params = array())
			{
				$this->getRequest()->setMethod("DELETE");
				$this->getRequest()->setParams($params);
				return $this->dispatch($url);
			}
			
			public function post($url, $params = array())
			{
				$this->getRequest()->setMethod("POST");
				$this->getRequest()->setParams($params);
				return $this->dispatch($url);
			}
			
			public function put($url, $params = array())
			{
				$this->getRequest()->setMethod("PUT");
				$this->getRequest()->setParams($params);
				return $this->dispatch($url);
			}
			
			public function xhr($url, $params = array())
			{
				$this->getRequest()->setMethod("POST");
				$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
				$this->getRequest()->setParams($params);
				return $this->dispatch($url);
			}
		
			public function dispatch($url = null)
		    {
		        // redirector should not exit
		        $redirector = \Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
		        $redirector->setExit(false);
		
		        // json helper should not exit
		        $json = \Zend_Controller_Action_HelperBroker::getStaticHelper('json');
		        $json->suppressExit = true;
		
		        $request    = $this->getRequest();
		        if (null !== $url) {
		            $request->setRequestUri($url);
		        }
		        $request->setPathInfo(null);
		     
		        $this->frontController
		             ->setRequest($request)
		             ->setResponse($this->getResponse())
		             ->throwExceptions(false)
		             ->returnResponse(true);
		        return $this->frontController->dispatch();
			}
			
		/**
		     * Retrieve front controller instance
		     * 
		     * @return Zend_Controller_Front
		     */
		    public function getFrontController()
		    {
		        if (null === $this->_frontController) {
		            $this->_frontController = config()->zend->frontController;
		        }
		        return $this->_frontController;
		    }
		
		    /**
		     * Retrieve test case request object
		     * 
		     * @return Zend_Controller_Request_Abstract
		     */
		    public function getRequest()
		    {
		        if (null === $this->_request) {
		            $this->_request = new \Midori_PHPUnit_Zend_HttpRequest();
		        }
		        return $this->_request;
		    }
		
		    /**
		     * Retrieve test case response object 
		     * 
		     * @return Zend_Controller_Response_Abstract
		     */
		    public function getResponse()
		    {
		        if (null === $this->_response) {
		            $this->_response = new \Midori_PHPUnit_Zend_HttpResponse();
		        }
		        return $this->_response;
		    }
		
		    /**
		     * Retrieve DOM query object
		     * 
		     * @return Zend_Dom_Query
		     */
		    public function getQuery()
		    {
		        if (null === $this->_query) {
		            require_once 'Zend/Dom/Query.php';
		            $this->_query = new Zend_Dom_Query;
		        }
		        return $this->_query;
		    }
			
		 	/**
		     * Rest all view placeholders
		     * 
		     * @return void
		     */
		    protected function _resetPlaceholders()
		    {
		        $registry = Zend_Registry::getInstance();
		        $remove   = array();
		        foreach ($registry as $key => $value) {
		            if (strstr($key, '_View_')) {
		                $remove[] = $key;
		            }
		        }
		
		        foreach ($remove as $key) {
		            unset($registry[$key]);
		        }
		    }
		    
		
		    /**
		     * Reset the request object
		     *
		     * Useful for test cases that need to test multiple trips to the server.
		     * 
		     * @return Zend_Test_PHPUnit_ControllerTestCase
		     */
		    public function resetRequest()
		    {
		        $this->_request = null;
		        return $this;
		    }
		
		    /**
		     * Reset the response object
		     *
		     * Useful for test cases that need to test multiple trips to the server.
		     * 
		     * @return Zend_Test_PHPUnit_ControllerTestCase
		     */
		    public function resetResponse()
		    {
		        $this->_response = null;
		        $this->_resetPlaceholders();
		        return $this;
		    }
		    
			/**
		     * Increment assertion count
		     * 
		     * @return void
		     */
		    protected function _incrementAssertionCount()
		    {
		        $stack = debug_backtrace();
		        foreach (debug_backtrace() as $step) {
		            if (isset($step['object']) 
		                && $step['object'] instanceof PHPUnit_Framework_TestCase
		            ) {
		                if (version_compare(\PHPUnit_Runner_Version::id(), '3.3.3', 'lt')) {
		                    $step['object']->incrementAssertionCounter();
		                } else {
		                    $step['object']->addToAssertionCount(1);
		                }
		                break;
		            }
		        }
		    }
		} //end class
	} //end if
}// end namespace