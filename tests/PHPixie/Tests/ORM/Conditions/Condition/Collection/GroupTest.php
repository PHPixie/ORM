<?php

namespace PHPixie\Tests\ORM\Conditions\Condition\Collection;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions\Condition\Collection\Group
 */
class GroupTest extends \PHPixie\Tests\Database\Conditions\Condition\Collection\GroupTest
{
    protected function operatorCondition()
    {
        return new \PHPixie\ORM\Conditions\Condition\Field\Operator('a', '=', 1);
    }
    
    protected function condition()
    {
        return new \PHPixie\ORM\Conditions\Condition\Collection\Group();
    }
}