<?php

namespace PHPixie\ORM;

class Values
{
    public function orderBy($field, $direction)
    {
        return new \PHPixie\ORM\Values\OrderBy(
            $field,
            $direction
        );
    }
    
    public function preloadProperty($propertyName, $options = array())
    {
        return new \PHPixie\ORM\Values\Preload\Property(
            $propertyName,
            $options
        );
    }
    
    public function cascadingPreloadProperty($propertyName, $options = array())
    {
        $preload = $this->preload();
        
        return new \PHPixie\ORM\Values\Preload\Property\Cascading(
            $propertyName,
            $preload,
            $options
        );
    }
    
    public function preload()
    {
        return new \PHPixie\ORM\Values\Preload(
            $this
        );
    }
    
    public function update()
    {
        return new \PHPixie\ORM\Values\Update(
            $this
        );
    }
    
    public function updateBuilder($query)
    {
        return new \PHPixie\ORM\Values\Update\Builder(
            $this,
            $query
        );
    }
    
    public function updateIncrement($amount)
    {
        return new \PHPixie\ORM\Values\Update\Increment($amount);
    }
    
    public function updateRemove()
    {
        return new \PHPixie\ORM\Values\Update\Remove();
    }
}