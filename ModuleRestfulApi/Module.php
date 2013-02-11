<?php

namespace ModuleRestfulApi;

use Zend\Mvc\MvcEvent;

/**
 *
 */
class Module
{
	/**
	 * @param MvcEvent $e
	 */
	public function onBootstrap(MvcEvent $e)
	{
		/** @var \Zend\ModuleManager\ModuleManager $moduleManager */
		$moduleManager = $e->getApplication()->getServiceManager()->get('modulemanager');
		/** @var \Zend\EventManager\SharedEventManager $sharedEvents */
		$sharedEvents = $moduleManager->getEventManager()->getSharedManager();
		
		$sharedEvents->attach('Zend\Mvc\Controller\AbstractRestfulController', MvcEvent::EVENT_DISPATCH, array($this, 'postProcess'), -100);
		$sharedEvents->attach('Zend\Mvc\Application', MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'errorProcess'), 999);
	}

	/**
	 * return array
	 */
	public function getAutoloaderConfig()
	{
	    return array(
	        'Zend\Loader\StandardAutoloader' => array(
	            'namespaces' => array(
	                __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
	            ),
	        ),
	    );
	}
	/**
	 * @return array
	 */
	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	/**
	 * @param MvcEvent $e
	 * @return null|\Zend\Http\PhpEnvironment\Response
	 */
	public function postProcess(MvcEvent $e)
	{
		/** @var \Zend\Di\Di $di */
		$di = $e->getTarget()->getServiceLocator()->get('di');
    
		$formatter = $this->getPostProcessor($e);
		
		if ($formatter !== false) {
			if ($e->getResult() instanceof \Zend\View\Model\ViewModel) {
				if (is_array($e->getResult()->getVariables())) {
					$vars = $e->getResult()->getVariables();
				} else {
					$vars = null;
				}
			} else {
				$vars = $e->getResult();
			}
			
			/** @var PostProcessor\AbstractPostProcessor $postProcessor */
			$postProcessor = $di->get(
		    $formatter, array(
				'response' => $e->getResponse(),
				'vars' => $vars,
			));

			$postProcessor->process();
			$response = $postProcessor->getResponse();
      
			return $response;
		}

		return null;
	}

	/**
	 * @param MvcEvent $e
	 * @return null|\Zend\Http\PhpEnvironment\Response
	 */
	public function errorProcess(MvcEvent $e)
	{
		/** @var \Zend\Di\Di $di */
		$di = $e->getApplication()->getServiceManager()->get('di');

		$eventParams = $e->getParams();

		/** @var array $configuration */
		$configuration = $e->getApplication()->getConfig();

		$vars = array(
		    'error' => array(),
		);
		
		if (isset($eventParams['exception'])) {
			/** @var \Exception $exception */
			$exception = $eventParams['exception'];

			if ($configuration['errors']['show_exceptions']['message']) {
				$vars['error']['message'] = $exception->getMessage();
			}
			if ($configuration['errors']['show_exceptions']['trace']) {
				$vars['error']['trace'] = $exception->getTrace();
			}
		}

		if (empty($vars)) {
			$vars['error'] = 'Something went wrong';
		}

		if (
			$eventParams['error'] === \Zend\Mvc\Application::ERROR_CONTROLLER_NOT_FOUND ||
			$eventParams['error'] === \Zend\Mvc\Application::ERROR_ROUTER_NO_MATCH
		) {
		  $vars['error']['message'] = 'The REST service was not found';
			$e->getResponse()->setStatusCode(\Zend\Http\PhpEnvironment\Response::STATUS_CODE_501);
		} else {
			$e->getResponse()->setStatusCode(\Zend\Http\PhpEnvironment\Response::STATUS_CODE_500);
		}
		
		/** @var PostProcessor\AbstractPostProcessor $postProcessor */
		$postProcessor = $di->get(
// 		    $configuration['errors']['post_processor'],
		    $this->getPostProcessor($e),
		    array('vars' => $vars, 'response' => $e->getResponse())
		);
		
		$postProcessor->process();
		
		$e->stopPropagation();

		return $postProcessor->getResponse();
	}
	
	public function getPostProcessor(MvcEvent $e) {
	    /** @var array $configuration */
	    $configuration = $e->getApplication()->getConfig();
	    
	    if(!isset($configuration['moduleApi_postprocessor']) || !isset($configuration['moduleApi_postprocessor']['formatters']))
	        throw new \Exception("Configuration entry moduleApi_postprocessor has not been found.");
	    
	    $formatterList = $configuration['moduleApi_postprocessor']['formatters'];
	    $formatterDefault = reset($formatterList);
	    
	    $formatter = $formatterDefault;
	    
	    $routeMatch = $e->getRouteMatch();
	    
	    if(null !== $routeMatch)
	        $formatter = $routeMatch->getParam('formatter', $formatterDefault);
	    
	    if(!in_array($formatter, $formatterList)) {
	        $formatter = $formatterDefault;
	    }
	    
	    return 'moduleApi-postprocessor-' . $formatter;
	}
}
