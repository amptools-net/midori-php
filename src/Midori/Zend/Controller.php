<?php 


namespace Midori\Zend
{
	
	class Controller extends \Zend_Controller_Action
	{
		private $_params = null;
		private $fields = array();
		
		public function initView()
		{
			
			$view = Midori\Zend\View::get();
			$config = Midori\config();
			
				$view
					->setBasePath($config->paths->app)
					->setEncoding("utf8");
			return $view;
		}
		
		public function __get($property)
		{
			switch($property)
			{
				case "params":
					if($this->_params ==  null)
					{
						$this->_params = new Params($this->getRequest()->getParams());		
					}
					return $this->_params;
				default:
					return $this->fields[$property];	
			}
		}
		
		public function __set($property, $value)
		{
			$this->fields[$property] = $value;	
		}
		
		
		/**
     * Dispatch the requested action
     *
     * @param string $action Method name of action
     * @return void
     */
    public function dispatch($action)
    {
    	 $action = str_replace("Action", "", $action);
    	
        // Notify helpers of action preDispatch state
        $this->_helper->notifyPreDispatch();

        $this->preDispatch();
        if ($this->getRequest()->isDispatched()) {
            if (null === $this->_classMethods) {
                $this->_classMethods = get_class_methods($this);
            }

            // preDispatch() didn't change the action, so we can continue
            if ($this->getInvokeArg('useCaseSensitiveActions') || in_array($action, $this->_classMethods)) {
                if ($this->getInvokeArg('useCaseSensitiveActions')) {
                    trigger_error('Using case sensitive actions without word separators is deprecated; please do not rely on this "feature"');
                }
              
                $this->$action();
                $this->view->setVars($this->fields);
            } else {
                $this->__call($action, array());
                 $this->view->setVars($this->fields);
            }
            $this->postDispatch();
        }

        // whats actually important here is that this action controller is
        // shutting down, regardless of dispatching; notify the helpers of this
        // state
        $this->_helper->notifyPostDispatch();
    }
		
	public function run(Zend_Controller_Request_Abstract $request = null, Zend_Controller_Response_Abstract $response = null)
    {
    	exit(0);
        if (null !== $request) {
            $this->setRequest($request);
        } else {
            $request = $this->getRequest();
        }
exit(0);
        if (null !== $response) {
            $this->setResponse($response);
        }

	echo $action;
	
        $action = $request->getActionName();
        if (empty($action)) {
            $action = 'index';
        }
        $action = $action;

        $request->setDispatched(true);
        $this->dispatch($action);

        return $this->getResponse();
    }
		
		
	}		
		
}