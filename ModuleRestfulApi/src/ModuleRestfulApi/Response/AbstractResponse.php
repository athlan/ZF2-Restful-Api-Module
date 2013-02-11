<?php 

namespace ModuleRestfulApi\Response;

use \Zend\Http\Response;

abstract class AbstractResponse
    implements \ArrayAccess
{
    const STATUS_CODE_SUCCESS = Response::STATUS_CODE_200;
    const STATUS_CODE_FAILURE = Response::STATUS_CODE_400;
    
    const STATUS_CODE_NOT_FOUND = Response::STATUS_CODE_404;
    const STATUS_CODE_VALIDATION_ERROR = Response::STATUS_CODE_400;
    
    const STATUS_CODE_CREATED = Response::STATUS_CODE_201;
    const STATUS_CODE_MODIFIED = Response::STATUS_CODE_201;
    
    private $statusCode = \Zend\Http\PhpEnvironment\Response::STATUS_CODE_200;
    
    private $params = [];
    
    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
    }
    
    public function getStatusCode() {
        return $this->statusCode;
    }
    
    /**
     * @param string $key
     * @param mixed $value
     */
    public function setParam($key, $value) {
        $this->param[$key] = $value;
    }
    
    /**
     * @param string $key
     */
    public function getParam($key) {
        return isset($this->params[$key]) ? $this->params[$key] : null;
    }
    
    public function unsetParam($key) {
        unset($this->param[$key]);
    }
    
    public function isParam($key) {
        return isset($this->param[$key]);
    }
    
    public function getParams() {
        return $this->params;
    }
    
    public function toArray() {
        return $this->getParams();
    }
    
    public function __set($key, $value) {
        $this->setParam($key, $value);
    }
    
    public function __get($key) {
        return $this->getParam($key);
    }
    
    public function offsetSet($offset, $value) {
        $this->setParam($offset, $value);
    }
    
    public function offsetExists($offset) {
        return $this->isParam($offset);
    }
    
    public function offsetUnset($offset) {
        $this->unParam($offset);
    }
    
    public function offsetGet($offset) {
        return $this->getParam($offset);
    }
}
