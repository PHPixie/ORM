<?php

namespace PHPixie\Tests\ORM\Drivers\Driver\Mongo;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\Mongo\Query
 */
class QueryTest extends \PHPixie\Tests\ORM\Models\Type\Database\Implementation\QueryTest
{
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\Mongo\Config');
    }
    
    protected function getEntity()
    {
        return $this->quickMock('\PHPixie\ORM\Drivers\Driver\Mongo\Entity');
    }
    
    protected function query()
    {
        return new \PHPixie\ORM\Drivers\Driver\Mongo\Query(
            $this->values,
            $this->queryMapper,
            $this->queryPropertyMap,
            $this->builder,
            $this->config
        );
    }
    
    protected function queryMock($methods)
    {
        return $this->getMock('\PHPixie\ORM\Drivers\Driver\Mongo\Query', $methods, array(
            $this->values,
            $this->queryMapper,
            $this->queryPropertyMap,
            $this->builder,
            $this->config
        ));
    }
}