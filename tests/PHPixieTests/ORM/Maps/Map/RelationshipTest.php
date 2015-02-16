<?php

namespace PHPixieTests\ORM\Sides\Map;

/**
 * @coversDefaultClass \PHPixie\ORM\Maps\Map\Relationship
 */
class RelationshipTest extends \PHPixieTests\ORM\Maps\MapTest
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