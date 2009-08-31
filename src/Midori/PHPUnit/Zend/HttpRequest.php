<?php
	
namespace Midori\PHPUnit\Zend
{	
	class Midori\PHPUnit\Zend\HttpRequest extends \Zend_Controller_Request_HttpTestCase
	{
		 /**
	     * Scheme for http
	     *
	     */
	    const SCHEME_HTTP  = 'http';
	    
	    /**
	     * Scheme for https
	     *
	     */
	    const SCHEME_HTTPS = 'https';
	
	    /**
	     * Allowed parameter sources
	     * @var array
	     */
	    protected $_paramSources = array('_GET', '_POST');
	
	    /**
	     * REQUEST_URI
	     * @var string;
	     */
	    protected $_requestUri;
	
	    /**
	     * Base URL of request
	     * @var string
	     */
	    protected $_baseUrl = null;
	
	    /**
	     * Base path of request
	     * @var string
	     */
	    protected $_basePath = null;
	
	    /**
	     * PATH_INFO
	     * @var string
	     */
	    protected $_pathInfo = '';
	
	    /**
	     * Instance parameters
	     * @var array
	     */
	    protected $_params = array();
	
	    /**
	     * Alias keys for request parameters
	     * @var array
	     */
	    protected $_aliases = array();
		
		/**
	     * Retrieve a member of the $_SERVER superglobal
	     *
	     * If no $key is passed, returns the entire $_SERVER array.
	     *
	     * @param string $key
	     * @param mixed $default Default value to use if key not found
	     * @return mixed Returns null if key does not exist
	     */
	    public function getServer($key = null, $default = null)
	    {
	        if (null === $key) {
	            return $_SERVER;
	        }
	
	        return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
	    }
	    
		
	 	/**
	     * Return the method by which the request was made
	     *
	     * @return string
	     */
	    public function getMethod()
	    {
	        return $this->getServer('REQUEST_METHOD');
	    }
	
	  /**
	     * Was the request made by POST?
	     *
	     * @return boolean
	     */
	    public function isPost()
	    {
	        if ('POST' == $this->getMethod()) {
	            return true;
	        }
	
	        return false;
	    }
	
	    /**
	     * Was the request made by GET?
	     *
	     * @return boolean
	     */
	    public function isGet()
	    {
	        if ('GET' == $this->getMethod()) {
	            return true;
	        }
	
	        return false;
	    }
	
	    /**
	     * Was the request made by PUT?
	     *
	     * @return boolean
	     */
	    public function isPut()
	    {
	        if ('PUT' == $this->getMethod()) {
	            return true;
	        }
	
	        return false;
	    }
	
	    /**
	     * Was the request made by DELETE?
	     *
	     * @return boolean
	     */
	    public function isDelete()
	    {
	        if ('DELETE' == $this->getMethod()) {
	            return true;
	        }
	
	        return false;
	    }
	
	    /**
	     * Was the request made by HEAD?
	     *
	     * @return boolean
	     */
	    public function isHead()
	    {
	        if ('HEAD' == $this->getMethod()) {
	            return true;
	        }
	
	        return false;
	    }
	
	    /**
	     * Was the request made by OPTIONS?
	     *
	     * @return boolean
	     */
	    public function isOptions()
	    {
	        if ('OPTIONS' == $this->getMethod()) {
	            return true;
	        }
	
	        return false;
	    }
	    
	    /**
	     * Is the request a Javascript XMLHttpRequest?
	     *
	     * Should work with Prototype/Script.aculo.us, possibly others.
	     *
	     * @return boolean
	     */
	    public function isXmlHttpRequest()
	    {
	        return ($this->getHeader('X_REQUESTED_WITH') == 'XMLHttpRequest');
	    }
	
	    /**
	     * Is this a Flash request?
	     * 
	     * @return bool
	     */
	    public function isFlashRequest()
	    {
	        $header = strtolower($this->getHeader('USER_AGENT'));
	        return (strstr($header, ' flash')) ? true : false;
	    }
	    
	    /**
	     * Is https secure request
	     *
	     * @return boolean
	     */
	    public function isSecure()
	    {
	        return ($this->getScheme() === self::SCHEME_HTTPS);
	    }
		
	}
}