<?php

namespace PHPixie\Tests\ORM\Drivers\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\PDO\Query
 */
class QueryTest extends \PHPixie\Tests\ORM\Drivers\Driver\SQL\QueryTest
{
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\PDO\Config');
    }
    
    protected function getEntity()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\PDO\Entity');
    }
    
    protected function query()
    {
        return new \PHPixie\ORM\Drivers\Driver\PDO\Query(
            $this->values,
            $this->queryMapper,
            $this->queryPropertyMap,
            $this->builder,
            $this->config
        );
    }
    
    protected function queryMock($methods)
    {
        return $this->getMock('\PHPixie\ORM\Drivers\Driver\PDO\Query', $methods, array(
            $this->values,
            $this->queryMapper,
            $this->queryPropertyMap,
            $this->builder,
            $this->config
        ));
    }
}