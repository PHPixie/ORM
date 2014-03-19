<?php

namespace PHPixie\ORM\Driver\Mongo\Repository;

class Embedded
{
    protected $parent;
    protected $class;
    
    public function __construct($parent, $config)
    {
        $this->parent = $parent;
        $this->class = $config->get('class');
    }
    
    

}
