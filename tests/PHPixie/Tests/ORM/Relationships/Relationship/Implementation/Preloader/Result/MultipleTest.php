<?php

namespace PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Preloader\Result;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result\Multiple
 */
abstract class MultipleTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Preloader\ResultTest
{
    protected function prepareMultiplePreloader($ids)
    {
        $loader = $this->getReusableResult();
        $this->method($this->loaders, 'multiplePreloader', $loader, array($this->preloader, $ids), 0);
        return $loader;
    }
    
    /**
     * @covers \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result::__construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result\Multiple::__construct
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
}