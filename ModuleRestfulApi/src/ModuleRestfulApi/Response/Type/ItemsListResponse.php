<?php 

namespace ModuleRestfulApi\Response\Type;

use ModuleRestfulApi\Response\AbstractResponse;

use ModuleRestfulApi\Response\ResponeItem;

class ItemsListResponse extends AbstractResponse
    implements \Countable
{
    protected $items = [];
    
    /**
     * 
     * @param $item ModuleModel\Entity\Util\ResponeItem
     */
    public function addItem($item) {
        if($item instanceof ResponeItem) {
            $item = $item->toResponseArray();
        }
        
        if(!is_array($item))
            throw new \Exception("Cannot add the item to response. It have to be an array or instance of ResponeItem");
        
        $this->items[] = $item;
    }
    
    public function addItems($items) {
        foreach($items as $item) {
            $this->addItem($item);
        }
    }
    
    public function getItems() {
        return $this->items;
    }
    
    public function count() {
        return count($this->items);
    }
    
    public function toArray() {
        $data = parent::toArray();
        
        $data['count'] = count($this);
        $data['items'] = [];
        
        foreach($this->items as $item) {
            $data['items'][] = $item;
        }
        
        return $data;
    }
}
