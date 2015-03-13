<?php

namespace PHPixie\Tests\ORM\Relationships\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo
 */
abstract class OneToTest extends \PHPixie\Tests\ORM\Relationships\Relationship\ImplementationTest
{
    /**
     * @covers ::preloader
     * @covers ::<protected>
     */
    public function testPreloader()
    {
        $configSlice = $this->configSlice();
        $sides = $this->relationship->getSides($configSlice);
        
        $modelConfig = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Config');
        $result = $this->abstractMock('\PHPixie\ORM\Steps\Result');
        $loader = $this->getLoader();
        
        $this->preloaderTest($sides, $modelConfig, $result, $loader);
    }
    
    abstract protected function preloaderTest($sides, $modelConfig, $result, $loader);
}