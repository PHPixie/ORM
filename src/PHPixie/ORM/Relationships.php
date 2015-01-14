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
    
    public function oneToOne()
    {
        return $this->get('oneToOne');   
    }
    
    public function oneToMany()
    {
        return $this->get('oneToMany');   
    }
    
    public function manyToMany()
    {
        return $this->get('manyToMany');   
    }
    
    public function embedsOne()
    {
        return $this->get('embedsOne');   
    }
    
    public function embedsMany()
    {
        return $this->get('embedsMany');   
    }
    
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
