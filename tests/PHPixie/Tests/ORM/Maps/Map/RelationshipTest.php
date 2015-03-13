<?php

namespace PHPixie\Tests\ORM\Maps\Map;

/**
 * @coversDefaultClass \PHPixie\ORM\Maps\Map\Relationship
 */
class RelationshipTest extends \PHPixie\Tests\ORM\Maps\MapTest
{    
    
    protected function getSide()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side\Relationship');
    }
    
    protected function map()
    {
        return new \PHPixie\ORM\Maps\Map\Relationship();
    }
}