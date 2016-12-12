<?php

namespace PHPixie\ORM;

/**
 * Class Drivers
 * @package PHPixie\ORM
 */
class Drivers
{
    /**
     * @type \PHPixie\ORM\Builder
     */
    protected $ormBuilder;
    /**
     * @var array
     */
    protected $drivers = array();

    /**
     * @var array
     */
    protected $classMap = array(
        'pdo'       => '\PHPixie\ORM\Drivers\Driver\PDO',
        'mongo'     => '\PHPixie\ORM\Drivers\Driver\Mongo',
        'interbase' => '\PHPixie\ORM\Drivers\Driver\InterBase',
    );

    /**
     * Drivers constructor.
     * @param Builder $ormBuilder
     */
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

    /**
     * @param string $name
     * @return \PHPixie\Database\Driver
     */
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