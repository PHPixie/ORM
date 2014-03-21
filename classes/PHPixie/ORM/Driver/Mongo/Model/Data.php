<?php

namespace PHPixie\ORM\Driver\Mongo\Model;

class Data extends \PHPixie\ORM\Model\Data
{
    public function setDataValue($key, $value)
    {
        if ($value instanceof static) {
            $value = $value->getData();
        
        if ($value instanceof \stdClass) {
            $value = (array) $value;
        
        if (is_array($value)) {
            $this->setGroup($key, $value);
        }
        
        $this->data[$key] = $value;
    }
    
    public function addGroup($key)
    {
        return $this->setGroup($key, array());
    }
    
    public function setGroup($key, $data)
    {
        $group = new static($data);
        $this->modified[$key] = $group;
    }
}
    