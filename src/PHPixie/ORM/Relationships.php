<?php

namespace PHPixie\ORM;

class Relationships
{
    protected $ormBuilder;
    protected $relationships = array();
    protected $classMap = array(
        'oneToOne'   => '\PHPixie\ORM\Relationships\Type\OneTo\Type\One',
        'oneToMany'  => '\PHPixie\ORM\Relationships\Type\OneTo\Type\Many',
        'manyToMany' => '\PHPixie\ORM\Relationships\Type\ManyToMany',
        'embedsOne'  => '\PHPixie\ORM\Relationships\Type\Embeds\Type\One',
        'embedsMany' => '\PHPixie\ORM\Relationships\Type\Embeds\Type\Many',
    );

    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }

    public function get($name)
    {
        if (!array_key_exists($name, $this->relationships))
        {
            $this->relationships[$name] = $this->buildRelationship($name);
        }
        
        return $this->relationships[$name];
    }

    /**
     * @param $name
     * @return Relationships\Type\OneTo\Type\One|Relationships\Type\OneTo\Type\Many|Relationships\Type\ManyToMany|Relationships\Type\Embeds\Type\One|Relationships\Type\Embeds\Type\Many
     * @throws Exception\Relationship
     */
    protected function buildRelationship($name) {
        if(!array_key_exists($name, $this->classMap)) {
            throw new \PHPixie\ORM\Exception\Relationship("Relationship type '$name' does not exist");
        }
        
        $class = $this->classMap[$name];
        return new $class(
            $this->ormBuilder->configs(),
            $this->ormBuilder->models(),
            $this->ormBuilder->planners(),
            $this->ormBuilder->plans(),
            $this->ormBuilder->steps(),
            $this->ormBuilder->loaders(),
            $this->ormBuilder->mappers()
        );
    }
}
