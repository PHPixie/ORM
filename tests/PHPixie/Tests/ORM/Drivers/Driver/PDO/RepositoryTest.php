<?php

namespace PHPixie\Tests\ORM\Drivers\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\PDO\Repository
 */
class RepositoryTest extends \PHPixie\Tests\ORM\Drivers\Driver\SQL\RepositoryTest
{
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\PDO\Config');
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
    
    protected function repository()
    {
        return new \PHPixie\ORM\Drivers\Driver\PDO\Repository(
            $this->databaseModel,
            $this->database,
            $this->dataBuilder,
            $this->config
        );
    }
}