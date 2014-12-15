<?php

namespace PHPixieTests\ORM\Relationships\Relationship\Implementation\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Value
 */
class ValueTest extends \PHPixieTests\ORM\Relationships\Relationship\Implementation\PreloaderTest
{
    protected $value;
    
    public function setUp()
    {
        $this->value = $this->getEntity();
        parent::setUp();
    }
    
    /**
     * @covers ::loadProperty
     * @covers ::<protected>
     */
    public function testLoadProperty()
    {
        $property = $this->getProperty();
        $this->method($property, 'setValue', null, array($this->value), 0);
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
        return new \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Value($this->value);
    }
    
}