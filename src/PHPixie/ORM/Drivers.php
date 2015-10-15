<?php

namespace PHPixie\ORM;

class Drivers
{
    /**
     * @type \PHPixie\ORM\Builder
     */
    protected $ormBuilder;
    protected $drivers = array();
    
    protected $classMap = array(
        'pdo'   => '\PHPixie\ORM\Drivers\Driver\PDO',
        'mongo' => '\PHPixie\ORM\Drivers\Driver\Mongo',
    );
    
    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }

    /**
     * @param $name
     * @return \PHPixie\ORM\Drivers\Driver\PDO|\PHPixie\ORM\Drivers\Driver\Mongo
     *
     */
    public function get($name)
    {
        if (!array_key_exists($name, $this->drivers))
        {
            $this->drivers[$name] = $this->buildDriver($name);
        }
        
        return $this->drivers[$name];
    }
    
    protected function buildDriver($name) {
        
        $class = $this->classMap[$name];

        return new $class(
            $this->ormBuilder->configs(),
            $this->ormBuilder->conditions(),
            $this->ormBuilder->data(),
            $this->ormBuilder->database(),
            $this->ormBuilder->models(),
            $this->ormBuilder->maps(),
            $this->ormBuilder->mappers(),
            $this->ormBuilder->values()
        );
    }
}