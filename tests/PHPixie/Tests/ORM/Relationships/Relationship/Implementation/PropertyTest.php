<?php

namespace PHPixie\Tests\ORM\Relationships\Relationship\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Implementation\Property
 */
abstract class PropertyTest extends \PHPixie\Test\Testcase
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
     * @covers \PHPixie\ORM\Relationships\Relationship\Implementation\Property::construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {

    }

    protected abstract function property();
    protected abstract function handler();
    protected abstract function side();
}
