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
            if(is_scalar($item)) {
                continue;
            }
            
            if($item->modelName() !== $this->modelName) {
                throw new \PHPixie\ORM\Exception\Builder("Model {$item->modelName()} does not match {$this->modelName}");
            }
            
            if($item instanceof \PHPixie\ORM\Models\Type\Database\Entity && $item->isNew()) {
                throw new \PHPixie\ORM\Exception\Builder("Only saved entities can be used");
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
