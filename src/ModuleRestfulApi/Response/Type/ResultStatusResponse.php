<?php 

namespace ModuleRestfulApi\Response\Type;

use ModuleRestfulApi\Response\ResponeItem;

use ModuleRestfulApi\Response\AbstractResponse;

class ResultStatusResponse extends AbstractResponse
{
    const STATUS_SUCCESS = 0x01;
    const STATUS_FAILURE = 0x02;
    
    /**
     * 
     * @var string|int
     */
    private $status = null;
    
    /**
     * 
     * @param string|int $status
     */
    public function setStatus($status) {
        $this->status = $status;
        
        if($status == self::STATUS_SUCCESS) {
            $this->setStatusCode(ResultStatusResponse::STATUS_CODE_SUCCESS);
        }
        elseif($status == self::STATUS_FAILURE) {
            $this->setStatusCode(ResultStatusResponse::STATUS_CODE_FAILURE);
        }
    }
    
    /**
     * 
     * @return string|int
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * 
     * @return string
     */
    public function getStatusAsString() {
        if(is_int($this->status))
            return self::getStatsCodeAsString($this->status);
        
        return (string) $this->status;
    }
    
    public function toArray() {
        $data = parent::toArray();
        $data['status'] = $this->getStatusAsString();
        
        return $data;
    }
    
    public static function getStatsCodeAsString($status) {
        if(!is_numeric($status))
            return '';
        
        if(!is_int($status))
            $status = (int)$status;
        
        switch ($status) {
            case self::STATUS_SUCCESS :
                return 'SUCCESS';
            
            case self::STATUS_FAILURE :
                return 'FAILURE';
        }
        
        return 'UNDEFINED';
    }
}
