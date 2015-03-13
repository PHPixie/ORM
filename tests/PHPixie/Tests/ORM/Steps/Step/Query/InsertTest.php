<?php

namespace PHPixie\Tests\ORM\Steps\Step\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Query\Insert
 */
class InsertTest extends \PHPixie\Tests\ORM\Steps\Step\QueryTest
{
    protected function query()
    {
        return $this->abstractMock('\PHPixie\Database\Query\Type\Insert');
    }
}