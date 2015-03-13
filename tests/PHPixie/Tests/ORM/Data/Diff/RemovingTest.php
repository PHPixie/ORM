<?php

namespace PHPixie\Tests\ORM\Data\Diff;

/**
 * @coversDefaultClass \PHPixie\ORM\Data\Diff\Removing
 */
class RemovingTest extends \PHPixie\Tests\ORM\Data\DiffTest
{
    protected $remove = array(
        'trees',
        'type'
    );
    
    /**
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testRemove()
    {
        return $this->assertEquals($this->remove, $this->diff->remove());
    }
    
    protected function getDiff()
    {
        return new \PHPixie\ORM\Data\Diff\Removing($this->set, $this->remove);
    }
}