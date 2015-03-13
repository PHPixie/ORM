<?php

namespace PHPixie\Tests\ORM\Drivers\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\PDO\Config
 */
class ConfigTest extends \PHPixie\Tests\ORM\Drivers\Driver\SQL\ConfigTest
{
    protected $driver = 'pdo';
    
    protected function getConfig($slice)
    {
        return new \PHPixie\ORM\Drivers\Driver\PDO\Config($this->inflector, $this->model, $slice);
    }
}