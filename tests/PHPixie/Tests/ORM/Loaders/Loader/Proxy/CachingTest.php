<?php

namespace PHPixie\Tests\ORM\Loaders\Loader\Proxy;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Proxy\Caching
 */
class CachingTest extends \PHPixie\Tests\ORM\Loaders\Loader\ProxyTest
{
    /**
     * @covers ::getByOffset
     * @covers ::offsetExists
     */
    public function testExistsGetByOffset()
    {
        $entity = $this->getEntity();

        $this->method($this->subloader, 'offsetExists', true, array(3), 0);
        $this->method($this->subloader, 'getByOffset', $entity, array(3), 1);
        $this->method($this->subloader, 'offsetExists', false, array(4), 2);

        $this->assertEquals(true, $this->loader->offsetExists(3));
        $this->assertEquals($entity, $this->loader->getByOffset(3));
        $this->assertEquals($entity, $this->loader->getByOffset(3));
        $this->assertEquals(true, $this->loader->offsetExists(3));
        $this->assertEquals(false, $this->loader->offsetExists(4));
    }
    
    protected function getLoader()
    {
        return new \PHPixie\ORM\Loaders\Loader\Proxy\Caching($this->loaders, $this->subloader);
    }
}