<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Preloader\Loader
 */
abstract class LoaderTest extends \PHPixieTests\ORM\Loaders\LoaderTest
{
    protected $config;
    protected $ownerLoader;
    protected $ownerPropertyName;

    public function setUp()
    {
        $this->config = $this->getConfig();
        $this->ownerLoader = $this->abstractMock('\PHPixie\ORM\Loaders\Loader');
        parent::setUp();
    }

    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Loaders\Loader::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {

    }

    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testGetByOffset()
    {
        $items = $this->prepareOffsets();
        foreach($items as $key => $item) {
            $this->assertEquals($item, $this->loader->getbyOffset($key));
        }
    }

    /**
     * @covers ::offsetExists
     * @covers ::<protected>
     */
    public function testOffsetExists()
    {
        $this->prepareOffsets();
        $this->assertEquals(true, $this->loader->offsetExists(10));
        $this->assertEquals(true, $this->loader->offsetExists(14));
        $this->assertEquals(false, $this->loader->offsetExists(15));
    }

    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testNotFoundException(){
        $items = $this->prepareOffsets();

        $this->setExpectedException('\PHPixie\ORM\Exception\Loader');
        $this->loader->getbyOffset(20);
    }

    protected function prepareOwnerProperties($count)
    {
        $owners = array();
        $properties = array();

        for($i=0; $i<$count; $i++) {
            $owners[$i] = $this->getEmbeddedModel();
            $properties[$i] = $this->getProperty();
            $this->method($owners[$i], 'relationshipProperty', $properties[$i], array($this->ownerPropertyName), null, true);
        }

        $this->method($this->ownerLoader, 'getByOffset', function($i) use ($owners){
            return $owners[$i];
        });

        $iterator = new \ArrayIterator($owners);
        $this->method($this->ownerLoader, 'getIterator', $iterator, array(), 0);
        return $properties;
    }

    protected function getEmbeddedModel()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Embedded\Model');
    }

    abstract protected function prepareOffsets();
    abstract protected function getConfig();
    abstract protected function getProperty();

}
