<?php

namespace PHPixie\ORM\Model\Data;

class Map extends \PHPixie\ORM\Model\Data
{
    protected $model;
    
    public function setModel($model)
    {
        $this->model = $model;
        foreach(get_object_vars($this->originalData) as $key => $value)
            $model->$key = $value;
    }
    
    public function currentData()
    {
        $currentProperties = $this->model->dataProperties();
        $current = new \stdClass;
        foreach($currentProperties as $key => $value)
            $current->$key = $value;
        
        return $currentData;
    }
    
    public function getDataDiff()
    {
        $originalData = get_object_vars($this->originalData);
        $currentData = get_object_vars($this->currentData());
        $unset = array_diff(array_keys($originalData), array_keys(currentData));
        $data = array_fill_keys($unset, null);
        
        foreach($currentData as $key => $value) {
            if (!array_key_exists($key, $originalData) || $value !== $originalData[$key])
                $data[$key] => $value;
        }
        
        return $data;
    }
    
    public function modelProperties()
    {
        return get_object_vars($this->originalData);
    }
    
}