<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Type\One\Property;

abstract class Model extends \PHPixie\ORM\Relationship\Type\Property\Model
{
    protected function processSet($model)
    {
        $this->unsetOpposing();
        $opposingProperty = $this->opposingProperty($model);
        $opposingProperty->unsetOpposing();
        $opposingProperty->setValue($this->model);
        $this->setValue($model);
    }
    
    protected function processUnset()
    {
        $opposingProperty = $this->opposingProperty($model);
        $opposingProperty->setValue(null);
        $this->setValue(null);
    }
    
    public function unsetOpposing()
    {
        if ($this->loaded || $this->value === null) {
            $opposingProperty = $this->opposingProperty($this->value);    
            if ($opposingProperty->loaded() && $opposingProperty->value() === $this->model)
                $opposingProperty->setValue(null);
        }
    }
    
    protected function opposingProperty($model)
    {
        $propertyName = $this->opposingPropertyName();
        return $model->$property;
    }
    
    abstract protected function opposingPropertyName();
}