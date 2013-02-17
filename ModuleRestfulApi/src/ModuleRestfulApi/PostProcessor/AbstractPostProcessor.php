<?php

namespace ModuleRestfulApi\PostProcessor;

/**
 *
 */
use ModuleRestfulApi\Response\AbstractResponse;

abstract class AbstractPostProcessor
{
	/**
	 * @var array|null
	 */
	protected $_vars = null;

	/**
	 * @var null|\Zend\Http\Response
	 */
	protected $_response = null;

	/**
	 * @param $vars
	 * @param \Zend\Http\Response $response
	 */
	public function __construct(\Zend\Http\Response $response, $vars = null)
	{
	  if($vars instanceof AbstractResponse) {
	      $responseType = $vars;
	      $vars = $responseType->toArray();
	      
	      $response->setStatusCode($responseType->getStatusCode());
	  }
	  
		$this->_vars = $vars;
		$this->_response = $response;
	}

	/**
	 * @return null|\Zend\Http\Response
	 */
	public function getResponse()
	{
		return $this->_response;
	}

	/**
	 * @abstract
	 */
	abstract public function process();
}
