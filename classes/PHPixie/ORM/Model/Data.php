<?php

namespace PHPixie\ORM\Model;

class Data {
    protected $data;
    protected $target;
    protected $propertiesSet = false;
    
    public function __construct($data, $target = null)
    {
        $this->data = $data;
        $this->target = $target !== null ? $target : $this;
        $this->setDataProperties();
    }
    
    public function setDataProperties()
    {
        foreach($this->data as $key => $value) {
            $this->target->$key = $value;
        }
    }
    
    public function currentData() {
        $targetData = get_object_vars($this->target);
        $classProperties = array_keys(get_class_vars(get_class($this->target)));
        foreach($classProperties as $property)
            unset($targetData[$property]);
        
        $currentData = new \stdClass;
        foreach($targetData as $key => $value) {
            var_dump($value);
            if ($value instanceof static)
                $value = $value->currentData();
            $currentData->$key = $value;
        }
        
        return $currentData;
    }
    
    public function modified()
    {
        $old = $this->data;
        $current = $this->currentData();
        var_dump([$current, $old]);
        return $this->objectDiff($current, $old);
    }
    
    public function objectDiff($new, $old, $prefix = null)
    {
        $newData = get_object_vars($new);
        $oldData = get_object_vars($old);
        
        $unset = array_diff(array_keys($oldData), array_keys($newData));
        
        if($prefix !== null)
            foreach($unset as &$val)
                $val = $prefix.'.'.$val;
        
        $set = array();
        
        foreach($newData as $key => $value) {
            $subPrefix = $prefix === null ? $key : $prefix.'.'.$key;
            if (!array_key_exists($key, $oldData)){
                $set[$subPrefix] = $value;
                continue;
            }
            
            $oldValue = $oldData[$key];
            
            $valueIsObject = $value instanceof \stdClass;
            $oldValueIsObject = $oldValue instanceof \stdClass;
            
            if (!$valueIsObject && !$oldValueIsObject) {
                if ($value !== $oldValue)
                    $set[$subPrefix] = $value;
            
            }elseif($valueIsObject && $oldValueIsObject) {
                list($subSet, $subUnset) = $this->objectDiff($value, $oldValue, $subPrefix);
                var_dump([$subSet, $subUnset]);
                $set = array_merge($set, $subSet);
                $unset = array_merge($unset, $subUnset);
                
            }else
                $set[$subPrefix] = $value;
            
        }
        
        return array($set, $unset);
    }
}

$old = new \stdClass;
$old->a = 5;
$old->b = 'pixie';
$old->c = new \stdClass;
$old->d = new \stdClass;
$old->d->da = new \stdClass;
$old->e = new \stdClass;

$old->c->ca = 'trixie';
$old->c->cb = array(5, 6);

$old->d->da->daa = 4;
$old->d->da->dab = 'test';
$old->d->da->dac = 6;

$old->d->db = 3;

$old->e->ea = 5;

$d = new Data($old);

unset($d->a);
$d->b = 'trixie';
$d->c->ca = 'pixie';
array_push($d->c->cb, 2);

$d->d->dc = new \stdClass;
$d->d->dc->dca = 5;
unset($d->d->da->daa);
$d->d->da->dab = 4;
    
$d->e = 8;
$d->f = 9;




print_r($d->modified());