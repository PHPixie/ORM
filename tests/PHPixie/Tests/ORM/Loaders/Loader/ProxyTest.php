<?php

namespace PHPixie\Tests\ORM\Loaders\Loader;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Prpxy
 */
abstract class ProxyTest extends \PHPixie\Tests\ORM\Loaders\LoaderTest
{
    protected $subloader;

    public function setUp()
    {
        $this->subloader = $this->quickMock('\PHPixie\ORM\Loaders\Loader');
        parent::setUp();
    }

    /**
     * @covers ::loader
     */
    public function testLoader()
    {
        $this->assertEquals($this->subloader, $this->loader->loader());
    }

    /**
     * @covers ::getByOffset
     */
    public function testNotFoundException(){
        $this->subloader
            ->expects($this->any())
            ->method('getByOffset')
            ->will($this->returnCallback(function(){
                throw new \PHPixie\ORM\Exception\Loader();
            }));

        $this->setExpectedException('\PHPixie\ORM\Exception\Loader');
        $this->loader->getbyOffset(4);
    }
    
}
