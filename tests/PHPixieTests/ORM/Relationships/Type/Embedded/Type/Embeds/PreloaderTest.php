<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Preloader
 */
class PreloaderTest extends \PHPixieTests\ORM\Relationships\Relationship\PreloaderTest
{
    /**
     * @covers ::loadProperty
     * @covers ::<protected>
     */
    public function testLoadProperty()
    {
        $property = $this->getProperty();
        $this->preloader->loadProperty($property);
    }
    
    protected function preloader()
    {
        return new \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Preloader(
            $this->loader
        );
    }
    
    protected function loader()
    {
        $this->abstractMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Preloader\Loader');
    }
    
    protected function getModel()
    {
        $this->abstractMock('\PHPixie\ORM\Repositories\Type\Embedded\Model');
    }
}
