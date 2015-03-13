<?php

namespace PHPixie\Tests\ORM\Maps\Map;

/**
 * @coversDefaultClass \PHPixie\ORM\Maps\Map\Cascade
 */
abstract class CascadeTest extends \PHPixie\Tests\ORM\Maps\MapTest
{
    /**
     * @covers ::hasModelSides
     * @covers ::<protected>
     */
    public function testHasModelSides()
    {
        $this->assertSame(false, $this->map->hasModelSides('pixie'));
        $side = $this->side('pixie', 'flowers');
        
        $this->map->add($side);
        $this->assertSame(true, $this->map->hasModelSides('pixie'));
    }
}