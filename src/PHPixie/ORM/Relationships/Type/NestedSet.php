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
        return new ManyToMany\Property\Query($this->handler(), $side, $query);
    }
    
    public function preloader($side, $modelConfig, $result, $loader, $parentResult)
    {
        if($side->type() === 'parent') {
            return $this->parentPreloader($side, $modelConfig, $result, $loader, $parentResult);
        }

        return $this->childrenPreloader($side, $modelConfig, $result, $loader, $parentResult);
    }

    protected function childrenPreloader($side, $modelConfig, $result, $loader, $parentResult)
    {
        return new NestedSet\Preloader\Children($this->loaders, $side, $modelConfig, $result, $loader, $parentResult);
    }

    public function parentPreloader($side, $modelConfig, $result, $loader, $parentResult)
    {
        return new NestedSet\Preloader\Parents($this->loaders, $side, $modelConfig, $result, $loader, $parentResult);
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
