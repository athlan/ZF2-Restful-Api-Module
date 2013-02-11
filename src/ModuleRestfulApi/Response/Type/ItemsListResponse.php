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
    public function addItem(ResponeItem $item) {
        $this->items[] = $item;
    }
    
    public function addItems(array $items) {
        foreach($items as $item) {
            if($item instanceof ResponeItem)
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
            /* @var $item \ModuleModel\Entity\Util\ResponeItem */
            $data['items'][] = $item->toResponseArray();
        }
        
        return $data;
    }
}
