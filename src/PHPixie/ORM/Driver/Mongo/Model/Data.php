<?php

namespace PHPixie\ORM\Driver\Mongo\Model;

class Data {
    protected $subdocument;
    protected $originalData;
    public function __construct($subdocument, $originalData) {
        $this->subdocument = $subdocument;
        $this->originalData = $originalData;
    }
    
    public function setModel($model)
    {
        $this->subdocument->setTarget($model);
    }
    
    public function modified()
    {
        return $this->getObjectDiff($this->subdocument->currentData(), $this->originalData);
    }
    
    protected function getObjectDiff($new, $old)
    {
        $newData = get_object_vars($new);
        $oldData = get_object_vars($old);
        $unset = array_diff(array_keys($oldData), array_keys($newData));
        
        $set = array();
        foreach($newData as $key => $value) {
            if (!array_key_exists($key, $oldData)) {
                $set[$key] = $value;
                continue;
            }
            
            $oldValue = $oldData[$key];
            
            if (is_object($value) && is_object($oldValue)) {
                $prefix = $key.'.';
                
                list($subSet, $subUnset) = $this->getObjectDiff($value, $oldValue);
                foreach($subSet as $subKey => $subValue) {
                    $set[$prefix.$subKey] = $subValue;
                }
                foreach($subUnset as $subKey => $subValue) {
                    $unset[$prefix.$subKey] = $subValue;
                }
                continue;
            }
            
            if (!$this->isEqual($value, $oldValue))
                $set[$key] = $value;
        }
        
        return array($set, $unset);
    }
    
    protected function isArrayEqual($new, $old)
    {
        if(array_keys($new) !== array_keys($old))
            return false;
            
        foreach($new as $key => $value)
            if(!$this->isEqual($value, $old[$key]))
                return false;
        
        return true;
    }
    
    protected function isObjectEqual($new, $old)
    {
        $newData = get_object_vars($new);
        $oldData = get_object_vars($old);
        return $this->isArrayEqual($newData, $oldData);
    }
    
    protected function isEqual($new, $old)
    {
        $type = gettype($new);
        
        if($type !== gettype($old))
            return false;
            
        if($type === 'array')
            return $this->isArrayEqual($new, $old);
            
        if ($type === 'object')
            return $this->isObjectEqual($new, $old);
        
        return $new === $old;
    }
}