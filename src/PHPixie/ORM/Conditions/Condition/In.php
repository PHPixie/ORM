<?php

namespace PHPixie\ORM\Conditions\Condition;

class In extends \PHPixie\ORM\Conditions\Condition\Implementation
{
    protected $modelName;
    protected $items = array();
    
    public function __construct($modelName, $items)
    {
        $this->modelName = $modelName;
        $this->add($items);
    }
    
    public function add($items)
    {
        if(!is_array($items)) {
            $items = array($items);
        }
        
        foreach($items as $item) {
            if($item->modelName() !== $this->modelName) {
                throw new \PHPixie\ORM\Exception\Builder("Model {$item->modelName()} does not match {$this->modelName}");
            }
        }
        
        foreach($items as $item) {
            $this->items[] = $item;
        }
    }
    
    public function items()
    {
        return $this->items;
    }
    
    public function modelName()
    {
        return $this->modelName;
    }
}
