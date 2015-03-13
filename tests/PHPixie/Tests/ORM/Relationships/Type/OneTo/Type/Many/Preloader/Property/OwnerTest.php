<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Property\Owner
 */
class OwnerTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\PreloaderTest
{
    protected $owner;
    
    public function setUp()
    {
        $this->owner = $this->getEntity();
        parent::setUp();
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::loadProperty
     * @covers ::<protected>
     */
    public function testLoadProperty()
    {
        $property = $this->getProperty();
        $this->method($property, 'setValue', null, array($this->owner), 0);
        $this->preloader->loadProperty($property);
    }
    
    protected function getEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
    
    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Entity\Owner');
    }
    
    protected function preloader()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Property\Owner(
            $this->owner
        );
    }
}