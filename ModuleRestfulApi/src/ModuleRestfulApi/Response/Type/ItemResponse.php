<?php 

namespace ModuleRestfulApi\Response\Type;

use ModuleRestfulApi\Response\ResponeItem;

use ModuleRestfulApi\Response\AbstractResponse;

class ItemResponse extends AbstractResponse
{
    /**
     * 
     * @var \ModuleApi\Response\ResponeItem
     */
    protected $item = null;
    
    public function __construct() {
        $this->setStatusCode(AbstractResponse::STATUS_CODE_NOT_FOUND);
    }
    
    public function setItem(ResponeItem $item) {
        $this->item = $item;
        
        if(null !== $item) {
            $this->setStatusCode(AbstractResponse::STATUS_CODE_SUCCESS);
        }
        else {
            $this->setStatusCode(AbstractResponse::STATUS_CODE_NOT_FOUND);
        }
    }
    
    public function getItem() {
        return $this->item;
    }
    
    public function toArray() {
        $data = parent::toArray();
        
        $data['item'] = (null !== $this->item) ? $this->item->toResponseArray() : null;
        
        return $data;
    }
}
