<?php

namespace PHPixieTests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers
 */
class MappersTest extends \PHPixieTests\AbstractORMTest
{
    protected $ormBuilder;
    
    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
}