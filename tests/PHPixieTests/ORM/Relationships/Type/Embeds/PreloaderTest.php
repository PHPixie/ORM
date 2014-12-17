<?php

namespace PHPixieTests\ORM\Relationships\Type\Embeds;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Preloader
 */
class PreloaderTest extends \PHPixieTests\ORM\Relationships\Relationship\Implementation\PreloaderTest
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
        return new \PHPixie\ORM\Relationships\Type\Embeds\Preloader(
            $this->loader
        );
    }
    
    protected function getProperty()
    {
        $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Implementation\Property\Entity');
    }
    
    protected function loader()
    {
        $this->abstractMock('\PHPixie\ORM\Relationships\Type\Embeds\Preloader\Loader');
    }
    
    protected function getEntity()
    {
        $this->abstractMock('\PHPixie\ORM\Models\Type\Embedded\Entity');
    }
}
