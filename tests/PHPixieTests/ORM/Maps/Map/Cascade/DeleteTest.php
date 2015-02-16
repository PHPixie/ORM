<?php

namespace PHPixieTests\ORM\Maps\Map\Cascade;

/**
 * @coversDefaultClass \PHPixie\ORM\Maps\Map\Cascade\Delete
 */
class DeleteTest extends \PHPixieTests\ORM\Maps\Map\CascadeTest
{
    protected function getSide()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side\Cascade\Delete');
    }
    
    protected function map()
    {
        return new \PHPixie\ORM\Maps\Map\Cascade\Delete();
    }
}