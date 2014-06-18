<?php

namespace PHPixieTests\ORM\Model\Data\Data;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Data\Data\Map
 */
class MapTest extends \PHPixieTests\ORM\Model\Data\DataTest
{
    public function setUp()
    {
        $this->originalData = (object) array(
            'name'    => 'Trixie',
            'flowers' => 3,
            'magic'   => 'air'
        );
        
        parent::setUp();
    }
    
    protected function getData()
    {
        return new \PHPixie\ORM\Model\Data\Data\Map($this->originalData);
    }
    
    public function testDiff()
    {
        $this->assertEquals(array(), $this->data->diff());
        $this->
        
    }
}