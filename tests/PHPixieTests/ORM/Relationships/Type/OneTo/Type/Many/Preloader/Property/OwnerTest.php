<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Many\Preloader\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Many\Preloader\Property\Owner
 */
class OwnerTest extends \PHPixieTests\ORM\Relationships\Relationship\PreloaderTest
{
    protected $owner;
    
    public function setUp()
    {
        $this->owner = $this->getModel();
        parent::setUp();
    }
    
    public function testValueFor()
    {
        $this->assertSame($this->owner, $this->preloader->valueFor($this->getModel()));
    }
    
    protected function getModel()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Database\Model');
    }
    
    protected function loader()
    {
        return $this->abstractMock('\PHPixie\ORM\Loaders\Loader\Repository');
    }
    
    protected function preloader()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Property\Owner($this->loader, $this->owner);
    }
}