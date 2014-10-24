<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Loader
 */
class LoaderTest extends \PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\LoaderTest
{
    /**
     * @covers ::offsetExists
     * @covers ::<protected>
     */
    public function testOffsetExists()
    {
        $this->prepareOffsets(5,3);
        $this->assertEquals(true, $this->loader->offsetExists(10));
        $this->assertEquals(true, $this->loader->offsetExists(14));
        $this->assertEquals(false, $this->loader->offsetExists(15));
    }

    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testGetByOffset()
    {
        $items = $this->prepareOffsets(5,3);
        foreach($items as $key => $item) {
            $this->assertEquals($item, $this->loader->getbyOffset($key));
            break;
        }
    }

    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testNotFoundException(){
        $items = $this->prepareOffsets(5,3);

        $this->setExpectedException('\PHPixie\ORM\Exception\Loader');
        $this->loader->getbyOffset(20);
    }

    protected function prepareOffsets($ownerCount, $itemCount)
    {
        $items = array();

        $owners = array();
        for($i=0; $i<$ownerCount; $i++) {
            $owners[$i] = $this->getEmbeddedModel();

            $ownerItems = array();
            for($j=0; $j<$itemCount; $j++) {
                $ownerItems[$j] = $this->getEmbeddedModel();
                $items[] = $ownerItems[$j];
            }

            $property = $this->getProperty();
            $this->method($owners[$i], 'relationshipProperty', $property, array('flowers'), null, true);
            $this->method($property, 'count', $itemCount, array(), 0);
            $this->method($property, 'offsetGet', $this->itemCallback($ownerItems));
        }

        $this->method($this->ownerLoader, 'getByOffset', function($i) use ($owners){
            return $owners[$i];
        });

        $iterator = new \ArrayIterator($owners);
        $this->method($this->ownerLoader, 'getIterator', $iterator, array(), 0);
        return $items;
    }

    protected function itemCallback($items)
    {
        return function($i) use($items) {
            return $items[$i];
        };
    }

    protected function getConfig()
    {
        $config = $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Side\Config');
        $config->ownerItemsProperty = 'flowers';
        return $config;
    }

    protected function getEmbeddedModel()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Embedded\Model');
    }

    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Property\Model\Items');
    }

    protected function getLoader()
    {
        return new \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Loader($this->loaders, $this->config, $this->ownerLoader);
    }
}
