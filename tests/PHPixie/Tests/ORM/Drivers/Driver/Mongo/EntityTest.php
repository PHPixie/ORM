<?php

namespace PHPixie\Tests\ORM\Drivers\Driver\Mongo;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\Mongo\Entity
 */
class EntityTest extends \PHPixie\Tests\ORM\Models\Type\Database\Implementation\EntityTest
{
    protected function getData()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Map');
    }
    
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\Mongo\Config');
    }
    
    protected function getRepository()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\Mongo\Repository');
    }
    
    protected function entity()
    {
        return $this->buildEntity();
    }
    
    protected function buildEntity($isNew = false)
    {
        $class = '\PHPixie\ORM\Drivers\Driver\Mongo\Entity';
        if($isNew) {
            return new $class($this->entityPropertyMap, $this->repository, $this->data, true);
        }else{
            return new $class($this->entityPropertyMap, $this->repository, $this->data);
        }
    }
}