<?php

namespace PHPixie\ORM;

class Mappers
{
    protected $ormBuilder;
    protected $instances = array();
    
    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }
    
    public function query()
    {
        return $this->instance('query');
    }
    
    public function conditions()
    {
        return $this->instance('conditions');
    }
    
    public function conditionsNormalizer()
    {
        return $this->instance('conditionsNormalizer');
    }
    
    public function conditionsOptimizer()
    {
        return $this->instance('conditionsOptimizer');
    }
    
    public function preload()
    {
        return $this->instance('preload');
    }
    
    public function update()
    {
        return $this->instance('update');
    }
    
    public function cascadeDelete()
    {
        return $this->instance('cascadeDelete');
    }
    
    public function cascadePath()
    {
        return new \PHPixie\ORM\Mappers\Cascade\Path($this);
    }
    
    protected function instance($name)
    {
        if(!array_key_exists($name, $this->instances)) {
            $method = 'build'.ucfirst($name);
            $this->instances[$name] = $this->$method();
        }
        
        return $this->instances[$name];
    }
    
    protected function buildQuery()
    {
        return new \PHPixie\ORM\Mappers\Query(
            $this,
            $this->ormBuilder->loaders(),
            $this->ormBuilder->models(),
            $this->ormBuilder->plans(),
            $this->ormBuilder->steps()
        );
    }
    
    protected function buildConditions()
    {
        return new \PHPixie\ORM\Mappers\Conditions(
            $this,
            $this->ormBuilder->relationships(),
            $this->ormBuilder->maps()->relationship()
        );
    }
    
    protected function buildConditionsOptimizer()
    {
        return new \PHPixie\ORM\Mappers\Conditions\Optimizer(
            $this,
            $this->ormBuilder->conditions()
        );
    }
    
    protected function buildConditionsNormalizer()
    {
        return new \PHPixie\ORM\Mappers\Conditions\Normalizer(
            $this->ormBuilder->conditions(),
            $this->ormBuilder->models()
        );
    }
    
    protected function buildPreload()
    {
        return new \PHPixie\ORM\Mappers\Preload(
            $this->ormBuilder->relationships(),
            $this->ormBuilder->maps()->preload()
        );
    }
    
    protected function buildUpdate()
    {
        return new \PHPixie\ORM\Mappers\Update();
    }
    
    protected function buildCascadeDelete()
    {
        return new \PHPixie\ORM\Mappers\Cascade\Mapper\Delete(
            $this,
            $this->ormBuilder->relationships(),
            $this->ormBuilder->models(),
            $this->ormBuilder->planners(),
            $this->ormBuilder->steps(),
            $this->ormBuilder->maps()->cascadeDelete()
        );
    }
    
}