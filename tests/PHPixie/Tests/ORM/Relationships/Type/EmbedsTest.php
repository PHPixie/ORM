<?php

namespace PHPixie\Tests\ORM\Relationships\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds
 */
abstract class EmbedsTest extends \PHPixie\Tests\ORM\Relationships\Relationship\ImplementationTest
{
    protected $preloaderClass;
    protected $preloadResultClass;
    
    /**
     * @covers ::preloader
     * @covers ::<protected>
     */
    public function testPreloader()
    {
        $preloader = $this->relationship->preloader();
        $this->assertInstanceOf($this->preloaderClass, $preloader);
    }
    
    /**
     * @covers ::preloadResult
     * @covers ::<protected>
     */
    public function testPreloadResult()
    {
        $reusableResult = $this->getReusableResult();
        $embeddedPrefix = 'fairies';
        
        $preloadResult = $this->relationship->preloadResult($reusableResult, $embeddedPrefix);
        $this->assertInstanceOf($this->preloadResultClass, $preloadResult);
        
        $this->assertProperties($preloadResult, array(
            'reusableResult' => $reusableResult,
            'embeddedPrefix' => $embeddedPrefix
        ));
    }

    protected function getReusableResult()
    {
        return $this->abstractMock('\PHPixie\ORM\Steps\Result\Reusable');
    }

    
    protected function getEntity()
    {
        return $this->getEmbeddedEntity();
    }

}