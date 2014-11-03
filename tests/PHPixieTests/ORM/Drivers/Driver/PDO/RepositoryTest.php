<?php

namespace PHPixieTests\ORM\Drivers\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\PDO\Repository
 */
class RepositoryTest extends \PHPixieTests\ORM\Drivers\Driver\SQL\RepositoryTest
{
    protected function repository()
    {
        return new \PHPixie\ORM\Drivers\Driver\PDO\Repository(
            $this->models,
            $this->database,
            $this->dataBuilder,
            $this->inflector,
            $this->modelName,
            $this->config
        );
    }
    
    protected function getConnection()
    {
        return $this->quickMock('\PHPixie\Database\Driver\PDO\Connection');
    }
    
    protected function getDatabaseQuery($type)
    {
        return $this->quickMock('\PHPixie\Database\Driver\PDO\Query\Type\\'.ucfirst($type));
    }
    
    protected function getQuery()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\PDO\Query');
    }
    
    protected function getEntity()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\PDO\Entity');
    }
}