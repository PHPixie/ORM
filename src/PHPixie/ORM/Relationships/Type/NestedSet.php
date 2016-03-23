<?php

namespace PHPixie\ORM\Relationships\Type;

class NestedSet extends \PHPixie\ORM\Relationships\Relationship\Implementation
                implements \PHPixie\ORM\Relationships\Relationship\Type\Database
{
    public function entityProperty($side, $entity)
    {
        if ($side->type() === 'parent') {
        //    return $this->parentEntityProperty($side, $entity);
        }
        
        return $this->childrenEntityProperty($side, $entity);
    }
    
    public function childrenEntityProperty($side, $entity)
    {
        return new NestedSet\Property\Children\Entity($this->handler(), $side, $entity);
    }
    
    public function queryProperty($side, $query)
    {
        return new ManyToMany\Property\Query($this->handler(), $side, $query);
    }
    
    public function preloader($side, $modelConfig, $result, $loader, $parentResult)
    {
        return new NestedSet\Preloader\Children($this->loaders, $side, $modelConfig, $result, $loader, $parentResult);
    }
    
    protected function config($configSlice)
    {
        return new NestedSet\Side\Config($this->configs->inflector(), $configSlice);
    }

    protected function side($type, $config)
    {
        return new NestedSet\Side($type, $config);
    }

    protected function sideTypes($config)
    {
        return array('children', 'parent');
    }
    
    protected function buildHandler()
    {
        return new NestedSet\Handler(
            $this->models,
            $this->planners,
            $this->plans,
            $this->steps,
            $this->loaders,
            $this->mappers,
            $this
        );
    }
}
