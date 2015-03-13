<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type\Many\Value\Preload;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Value\Preload\Owner
 */
class OwnerTest extends \PHPixie\Tests\ORM\Values\Preload\PropertyTest
{
    protected $owner;
    
    public function setUp()
    {
        $this->owner = $this->getEntity();
        parent::setUp();
    }
    
    /**
     * @covers ::owner
     * @covers ::<protected>
     */
    public function testOwner()
    {
        $this->assertSame($this->owner, $this->property->owner());
    }
    
    protected function getEntity()
    {
        $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Value\Preload\Owner(
            $this->propertyName,
            $this->owner
        );
    }
}