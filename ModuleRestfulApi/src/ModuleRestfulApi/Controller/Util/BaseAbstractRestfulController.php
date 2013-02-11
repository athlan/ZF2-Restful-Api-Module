<?php
namespace ModuleRestfulApi\Controller\Util;

use Zend\Http\Request;

use Zend\Mvc\MvcEvent;

use Zend\Mvc\Controller\AbstractRestfulController;

abstract class BaseAbstractRestfulController extends AbstractRestfulController
{
    /**
     * Handle the request
     *
     * @param  MvcEvent $e
     * @return mixed
     * @throws Exception\DomainException if no route matches in event or invalid HTTP method
     */
    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        if (!$routeMatch) {
            /**
             * @todo Determine requirements for when route match is missing.
             *       Potentially allow pulling directly from request metadata?
             */
            throw new Exception\DomainException('Missing route matches; unsure how to retrieve action');
        }
    
        $request = $e->getRequest();
        $action  = $routeMatch->getParam('action', false);
        if ($action) {
            // Handle arbitrary methods, ending in Action
            $method = static::getMethodFromAction($action);
            if (!method_exists($this, $method)) {
                $method = 'notFoundAction';
            }
            $return = $this->$method();
        } else {
            // RESTful methods
            switch (strtolower($request->getMethod())) {
                case 'get':
                    if (null !== $id = $routeMatch->getParam('id')) {
                        $action = 'get';
                        $return = $this->get($id);
                        break;
                    }
                    if (null !== $id = $request->getQuery()->get('id')) {
                        $action = 'get';
                        $return = $this->get($id);
                        break;
                    }
                    $action = 'getList';
                    $return = $this->getList();
                    break;
                case 'post':
                    if (null !== $id = $routeMatch->getParam('id')) {
                        $action = 'update';
                        $return = $this->processPostDataUpdate($request, $routeMatch);
                        break;
                    }
                    if (null !== $id = $request->getQuery()->get('id')) {
                        $action = 'update';
                        $return = $this->processPostDataUpdate($request, $routeMatch);
                        break;
                    }
                    $action = 'create';
                    $return = $this->processPostData($request);
                    break;
                case 'put':
                    $action = 'update';
                    $return = $this->processPutData($request, $routeMatch);
                    break;
                case 'delete':
                    if (null === $id = $routeMatch->getParam('id')) {
                        if (!($id = $request->getQuery()->get('id', false))) {
                            throw new Exception\DomainException('Missing identifier');
                        }
                    }
                    $action = 'delete';
                    $return = $this->delete($id);
                    break;
                default:
                    throw new Exception\DomainException('Invalid HTTP method!');
            }
    
            $routeMatch->setParam('action', $action);
        }
    
        // Emit post-dispatch signal, passing:
        // - return from method, request, response
        // If a listener returns a response object, return it immediately
        $e->setResult($return);
    
        return $return;
    }
    
    /**
     * Process post data and call update
     *
     * @param Request $request
     * @param $routeMatch
     * @return mixed
     * @throws Exception\DomainException
     */
    public function processPostDataUpdate(Request $request, $routeMatch)
    {
        if (null === $id = $routeMatch->getParam('id')) {
            if (!($id = $request->getQuery()->get('id', false))) {
                throw new Exception\DomainException('Missing identifier');
            }
        }
        
        return $this->update($id, $request->getPost()->toArray());
    }
}
