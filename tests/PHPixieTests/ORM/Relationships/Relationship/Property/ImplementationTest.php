<?php

namespace PHPixieTests\ORM\Relationships\Relationship\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Property\Implementation
 */
abstract class ImplementationTest extends \PHPixieTests\AbstractORMTest
{
    protected $property;
    protected $handler;
    protected $side;

    public function setUp()
    {
        $this->handler  = $this->handler();
        $this->side     = $this->side();
        $this->property = $this->property();
    }

    /**
     * @covers ::construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Property::construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {

    }

    protected function getModel(){
        return $this->abstractMock('\PHPixie\ORM\Model');
    }

    protected abstract function property();
    protected abstract function handler();
    protected abstract function side();
}
