<?php

namespace PHPixie\Tests\ORM\Drivers\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\PDO\Entity
 */
class EntityTest extends \PHPixie\Tests\ORM\Drivers\Driver\SQL\EntityTest
{
    protected function getData()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Map');
    }
    
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\PDO\Config');
    }
    
    protected function getRepository()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\PDO\Repository');
    }
    
    protected function entity()
    {
        return $this->buildEntity();
    }
    
    protected function buildEntity($isNew = false)
    {
        $class = '\PHPixie\ORM\Drivers\Driver\PDO\Entity';
        if($isNew) {
            return new $class($this->entityPropertyMap, $this->repository, $this->data, true);
        }else{
            return new $class($this->entityPropertyMap, $this->repository, $this->data);
        }
    }
}