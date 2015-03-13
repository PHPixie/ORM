<?php

namespace PHPixie\Tests\ORM\Conditions\Condition\Collection;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions\Condition\Collection\Placeholder
 */
class PlaceholderTest extends \PHPixie\Tests\Database\Conditions\Condition\Collection\PlaceholderTest
{

    protected function getContainer()
    {
        return $this->quickMock('\PHPixie\ORM\Conditions\Builder\Container', array('getConditions'));
    }
    
    protected function placeholder($allowEmpty = true)
    {
        return new \PHPixie\ORM\Conditions\Condition\Collection\Placeholder($this->container, $allowEmpty);   
    }
    
}