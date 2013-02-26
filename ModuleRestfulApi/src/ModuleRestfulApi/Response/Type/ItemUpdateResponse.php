<?php 

namespace ModuleRestfulApi\Response\Type;

use Zend\InputFilter\InputFilterInterface;

use ModuleRestfulApi\Response\ResponeItem;

use ModuleRestfulApi\Response\AbstractResponse;

class ItemUpdateResponse extends MultiMessageResultStatusResponse
{
    /**
     *
     * @var \ModuleApi\Response\ResponeItem
     */
    protected $item = null;
    
    /**
     *
     * @var \Zend\InputFilter\InputFilterInterface
     */
    private $inputFilter = null;
    
    public function setItem(ResponeItem $item) {
        $this->item = $item;
    }
    
    public function getItem() {
        return $this->item;
    }
    
    /**
     * @return the $inputFilter
     */
    public function getInputFilter() {
        return $this->inputFilter;
    }
    
	  /**
     * @param \Zend\InputFilter\InputFilterInterface $inputFilter
     */
    public function setInputFilter(InputFilterInterface $inputFilter) {
        $this->inputFilter = $inputFilter;
    }
    
    public function flushInputFilter() {
        if(null === $this->inputFilter)
            throw new \Exception("No InputFilter to flush");
        
        if($this->inputFilter->isValid()) {
            $this->setStatus(self::STATUS_SUCCESS);
        }
        else {
            $this->setStatus(self::STATUS_FAILURE);
            $this->setStatusCode(self::STATUS_CODE_VALIDATION_ERROR);
        }
//         var_dump($this->inputFilter->getMessages());exit;
//         die('aa');
        foreach ($this->inputFilter->getMessages() as $key => $messages) {
            $this->addMessage($key . ": " . implode(' ', array_values($messages)));
        }
    }
    
    public function toArray() {
        $params = parent::toArray();
        
        if(null !== $this->item)
            $params['item'] = $this->item->toResponseArray();
        
        return $params;
    }
}
