<?php

namespace PHPixie\ORM\Relationships\Type;

class NestedSet extends \PHPixie\ORM\Relationships\Relationship\Implementation
                implements \PHPixie\ORM\Relationships\Relationship\Type\Database
{
    protected $nestedSetSteps;

    public function entityProperty($side, $entity)
    {
        if ($side->type() === 'parent') {
            return $this->parentEntityProperty($side, $entity);
        }
        
        return $this->childrenEntityProperty($side, $entity);
    }
    
    protected function childrenEntityProperty($side, $entity)
    {
        return new NestedSet\Property\Children\Entity($this->handler(), $side, $entity);
    }

    protected function parentEntityProperty($side, $entity)
    {
        return new NestedSet\Property\Parent\Entity($this->handler(), $side, $entity);
    }
    
    public function queryProperty($side, $query)
    {
        return new NestedSet\Property\Query($this->handler(), $side, $query);
    }
    
    public function preloader($side, $modelConfig, $result, $loader, $parentResult, $relatedLoader)
    {
        return new NestedSet\Preloader($this->loaders, $side, $modelConfig, $result, $loader, $parentResult, $relatedLoader);
    }

    protected function config($configSlice)
    {
        return new NestedSet\Side\Config($this->configs->inflector(), $configSlice);
    }

    protected function side($type, $config)
    {
        if(in_array($type, array('children', 'parent'))) {
            return $this->buildEntitySide($type, $config);
        }
        
        return $this->buildSide($type, $config);
    }
    
    protected function buildSide($type, $config)
    {
        return new NestedSet\Side($type, $config);
    }
    
    protected function buildEntitySide($type, $config)
    {
        return new NestedSet\Side\Entity($type, $config);
    }

    protected function sideTypes($config)
    {
        return array('children', 'parent', 'allChildren', 'allParents');
    }

    public function steps()
    {
        if($this->nestedSetSteps === null) {
            $this->nestedSetSteps = $this->buildSteps();
        }

        return $this->nestedSetSteps;
    }

    protected function buildSteps()
    {
        return new NestedSet\Steps();
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
