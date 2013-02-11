<?php 

namespace ModuleRestfulApi\Response\Type;

use ModuleRestfulApi\Response\ResponeItem;

use ModuleRestfulApi\Response\AbstractResponse;

class MultiMessageResultStatusResponse extends ResultStatusResponse
{
    /**
     * 
     * @var string
     */
    private $messages = [];
    
	  /**
     * @return string
     */
    public function getMessages() {
        return $this->messages;
    }
    
    public function clearMessages() {
        $this->messages = [];
    }
    
    /**
     * @param string $message
     */
    public function addMessage($message) {
        $this->messages[] = $message;
    }
    
    /**
     * @param array[string] $messages
     */
    public function setMessages(array $messages) {
        $this->messages = $messages;
    }
    
    public function toArray() {
        $params = parent::toArray();
        
        if(0 !== count($this->messages))
            $params['messages'] = $this->messages;
        
        return $params;
    }
}
