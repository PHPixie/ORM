<?php

namespace \PHPixie\ORM\Driver\Mongo\Model\Data\Type;

class Subdocument extends \PHPixie\ORM\Driver\Mongo\Model\Data\Type {
    protected $data;
    protected $target;
    protected $propertiesSet = false;
    
    public function __construct($types, $data)
    {
		parent::__construct($types);
        $this->data = $data;
        $this->target = $this;
        $this->setDataProperties();
    }
    
    public function setDataProperties()
    {
        foreach($this->data as $key => $value) {
            $this->target->$key = $this->types->convertValue($value);
        }
    }
    
    public function currentProperties() {
        $currentData = get_object_vars($this->target);
        $classProperties = array_keys(get_class_vars(get_class($this->target)));
        foreach($classProperties as $property)
            unset($currentData[$property]);
		return $currentData;
    }
    
    public function modified()
    {
        $oldData = get_object_vars($this->data);
        $currentProperties = $this->currentProperties();
		$unset = array_diff(array_keys($oldData), array_keys($currentProperties));
		$set = array();
		
		foreach($currentProperties as $key => $value) {
			if (array_key_exists($key, $oldData)) {
				if ($value instanceof \PHPixie\ORM\Driver\Mongo\Model\Data\Type) {
					if (!$value-> isModified())
						continue;
					
					if ($value instanceof static) {
						list($subSet, $subUnset) = $value->modified();
					}
				}elseif($value === $oldData[$key])
					continue;
			}
			
			$set[$key] = $this->convertType($value);
        }
		
        return array($set, $unset);
    }
	
	public function isModified()
	{
		$oldData = get_object_vars($this->data);
        $currentProperties = $this->currentProperties();
		return $this->isDataModified($currentProperties, $oldData)
	}
	
	public function currentData()
	{
		$currentProperties = $this->getCurrentProperties();
		$current = new \stdClass;
		foreach($currentProperties as $key => $value)
			$current->$key = $this->convertType($value);
		return $current;
	}
    
}
/*
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




print_r($d->modified());*/