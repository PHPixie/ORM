<?php

namespace PHPixie\ORM;

/**
 * Class Mappers
 * @package PHPixie\ORM
 */
class Mappers
{
    /**
     * @type \PHPixie\ORM\Builder
     */
    protected $ormBuilder;
    /**
     * @var array
     */
    protected $instances = array();

    /**
     * Mappers constructor.
     * @param $ormBuilder \PHPixie\ORM\Builder
     */
    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }

    /**
     * @return \PHPixie\ORM\Mappers\Query
     */
    public function query()
    {
        return $this->instance('query');
    }

    /**
     * @return \PHPixie\ORM\Mappers\Conditions
     */
    public function conditions()
    {
        return $this->instance('conditions');
    }

    /**
     * @return \PHPixie\ORM\Mappers\Conditions\Normalizer
     */
    public function conditionsNormalizer()
    {
        return $this->instance('conditionsNormalizer');
    }

    /**
     * @return \PHPixie\ORM\Mappers\Conditions\Optimizer
     */
    public function conditionsOptimizer()
    {
        return $this->instance('conditionsOptimizer');
    }

    /**
     * @return \PHPixie\ORM\Mappers\Preload
     */
    public function preload()
    {
        return $this->instance('preload');
    }

    /**
     * @return \PHPixie\ORM\Mappers\Update
     */
    public function update()
    {
        return $this->instance('update');
    }

    /**
     * @return \PHPixie\ORM\Mappers\Cascade\Mapper\Delete
     */
    public function cascadeDelete()
    {
        return $this->instance('cascadeDelete');
    }

    /**
     * @return Mappers\Cascade\Path
     */
    public function cascadePath()
    {
        return new \PHPixie\ORM\Mappers\Cascade\Path($this);
    }

    /**
     * @param $name string
     * @return mixed
     */
    protected function instance($name)
    {
        if(!array_key_exists($name, $this->instances)) {
            $method = 'build'.ucfirst($name);
            $this->instances[$name] = $this->$method();
        }
        
        return $this->instances[$name];
    }

    /**
     * @return Mappers\Query
     */
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

    /**
     * @return Mappers\Conditions
     */
    protected function buildConditions()
    {
        return new \PHPixie\ORM\Mappers\Conditions(
            $this,
            $this->ormBuilder->planners(),
            $this->ormBuilder->relationships(),
            $this->ormBuilder->maps()->relationship()
        );
    }

    /**
     * @return Mappers\Conditions\Optimizer
     */
    protected function buildConditionsOptimizer()
    {
        return new \PHPixie\ORM\Mappers\Conditions\Optimizer(
            $this,
            $this->ormBuilder->conditions()
        );
    }

    /**
     * @return Mappers\Conditions\Normalizer
     */
    protected function buildConditionsNormalizer()
    {
        return new \PHPixie\ORM\Mappers\Conditions\Normalizer(
            $this->ormBuilder->conditions(),
            $this->ormBuilder->models()
        );
    }

    /**
     * @return Mappers\Preload
     */
    protected function buildPreload()
    {
        return new \PHPixie\ORM\Mappers\Preload(
            $this->ormBuilder->relationships(),
            $this->ormBuilder->maps()->preload()
        );
    }

    /**
     * @return Mappers\Update
     */
    protected function buildUpdate()
    {
        return new \PHPixie\ORM\Mappers\Update();
    }

    /**
     * @return Mappers\Cascade\Mapper\Delete
     */
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