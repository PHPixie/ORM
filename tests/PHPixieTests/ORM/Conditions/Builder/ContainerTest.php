<?php

namespace PHPixieTests\ORM\Conditions\Builder;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions\Builder\Container
 */
class ContainerTest extends \PHPixieTests\Database\Conditions\Builder\ContainerTest
{
    protected function conditions()
    {
        return new \PHPixie\ORM\Conditions;
    }
    
    protected function container($defaultOperator = '=')
    {
        return new \PHPixie\ORM\Conditions\Builder\Container($this->conditions, $defaultOperator);
    }
}