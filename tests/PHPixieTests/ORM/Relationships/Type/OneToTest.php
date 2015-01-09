<?php

namespace PHPixieTests\ORM\Relationships\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo
 */
abstract class OneToTest extends \PHPixieTests\ORM\Relationships\Relationship\ImplementationTest
{
    /**
     * @covers ::preloader
     * @covers ::<protected>
     */
    public function testPreloader()
    {
        $configSlice = $this->configSlice();
        $sides = $this->relationship->getSides($configSlice);
        
        $loader = $this->getLoader();
        
        $this->preloaderTest($sides, $loader);
    }
    
    abstract protected function preloaderTest($sides, $loader);
}