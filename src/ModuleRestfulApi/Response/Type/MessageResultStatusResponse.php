<?php 

namespace ModuleRestfulApi\Response\Type;

use ModuleRestfulApi\Response\ResponeItem;

use ModuleRestfulApi\Response\AbstractResponse;

class MessageResultStatusResponse extends ResultStatusResponse
{
    /**
     * 
     * @var string
     */
    private $message = null;
    
	  /**
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }
    
	  /**
     * @param string $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }
    
    public function toArray() {
        $params = parent::toArray();
        
        if(null !== $this->message)
            $params['message'] = $this->message;
        
        return $params;
    }
}
