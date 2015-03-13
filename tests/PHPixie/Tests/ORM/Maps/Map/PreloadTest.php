<?php

namespace PHPixie\Tests\ORM\Maps\Map;

/**
 * @coversDefaultClass \PHPixie\ORM\Maps\Map\Preload
 */
class PreloadTest extends \PHPixie\Tests\ORM\Maps\MapTest
{    
    
    protected function getSide()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side\Preload');
    }
    
    protected function map()
    {
        return new \PHPixie\ORM\Maps\Map\Preload();
    }
}