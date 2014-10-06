<?php

namespace PHPixieTests\ORM\Relationships\Type\Embeds\Type\Many;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Handler
 */
class HandlerTest extends \PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\HandlerTest
{
    protected $ownerPropertyName = 'ownerItemsProperty';
    protected $configOwnerProperty = 'flowers';

    /**
     * @covers ::offsetSet
     * @covers ::<protected>
     */
    public function testOffsetSet() {
        $this->offsetSetTest(1);
        $this->offsetSetTest(1, 5, true);
        $this->offsetSetTest(6, 5);
        $this->offsetSetTest(5, 5);
        $this->offsetSetTest(null);
    }

    /**
     * @covers ::offsetSet
     * @covers ::<protected>
     */
    public function testOffsetSetWrongModel() {
        $owner = $this->getOwner();
        $item = $this->prepareWrongItem();
        $this->handler->offsetSet($owner['model'], $this->propertyConfig, 1, $item);
    }

    /**
     * @covers ::offsetUnset
     * @covers ::<protected>
     */
    public function testOffsetUnset() {
        $owner = $this->getOwner('many', null, true);
        $loaderOffset = 0;
        $this->prepareUnsetItems($owner, array(1), $loaderOffset);
        $this->handler->offsetUnset($owner['model'], $this->propertyConfig, 1);
    }

    /**
     * @covers ::removeItems
     * @covers ::<protected>
     */
    public function testRemoveItems() {
        $this->removeItemsTest(array(1, 3));
        $this->removeItemsTest(array(1));
    }

    /**
     * @covers ::removeItems
     * @covers ::<protected>
     */
    public function testOffsetRemoveWrongModel() {
        $owner = $this->getOwner();
        $item = $this->prepareWrongItem();
        $this->handler->removeItems($owner['model'], $this->propertyConfig, $item);
    }

    /**
     * @covers ::offsetCreate
     * @covers ::<protected>
     */
    public function testOffsetCreate() {
        $this->offsetCreateTest(3);
        $this->offsetCreateTest(6, 5);
    }

    /**
     * @covers ::removeAllItems
     * @covers ::<protected>
     */
    public function testRemoveAllItems()
    {
        $owner = $this->getOwner('many', null, true);
        foreach($owner['cachedModels'] as $item) {
            $this->method($item, 'unsetOwnerRelationship', null, array(), 0);
        }
        $this->method($owner['loader'], 'clearCachedModels', null, array(), 1);
        $arrayNode = $this->prepareGetArrayNode($owner['document'], $this->configData['path']);
        $this->method($arrayNode, 'clear', null, array(), 0);
        $this->handler->removeAllItems($owner['model'], $this->propertyConfig);
    }

    /**
     * @covers ::loadProperty
     * @covers ::<protected>
     */
    public function testLoadProperty()
    {
        $owner = $this->getOwner();

        $itemRepository = $this->getEmbeddedRepository();
        $this->setRepositories(array(
            $this->configData['itemModel'] => $itemRepository
        ));

        $arrayNode = $this->prepareGetArrayNode($owner['document'], $this->configData['path']);
        $loader = $this->getArrayNodeLoader();
        $this->method($this->loaders, 'arrayNode', $loader, array($itemRepository, $owner['model'], $arrayNode), 0);
        $this->assertSame($loader, $this->handler->loadProperty($this->propertyConfig, $owner['model']));
    }

    protected function offsetSetTest($key, $count = 5, $withOldOwner = false)
    {
        $oldOwner = null;
        if($withOldOwner) {
            $oldOwner = $this->getOwner('many', $this->oldOwnerProperty);
        }

        $item = $this->getItem($oldOwner);
        $owner = $this->getOwner();
        $this->prepareSetItem($owner, $item, $key, $count);
        $this->handler->offsetSet($owner['model'], $this->propertyConfig, $key, $item['model']);
    }

    protected function prepareSetItem($owner, $item, $key, $count)
    {
        if($key === null) {
            $key = $count;
        }

        $arrayNode = $this->prepareGetArrayNode($owner['document'], $this->configData['path']);
        $this->method($arrayNode, 'count', $count, array(), 0);

        if($key <= $count) {
            $this->method($owner['loader'], 'cacheModel', null, array($key, $item['model']), 0);
            $this->method($arrayNode, 'offsetSet', null, array($key, $item['document']), 1);
        }else{
            $this->setExpectedException('\PHPixie\ORM\Exception\Relationship');
        }
    }

    protected function removeItemsTest($keys)
    {
        $owner = $this->getOwner('many', null, true);

        $remove = array();
        foreach($keys as $key) {
            $remove[] = $owner['cachedModels'][$key];
        }

        if(count($remove) == 1) {
            $remove = current($remove);
        }

        $loaderOffset = 1;
        $this->prepareUnsetItems($owner, $keys, $loaderOffset, 1);
        $this->handler->removeItems($owner['model'], $this->propertyConfig, $remove);
    }

    protected function prepareUnsetItems($owner, $offsets, &$loaderOffset, $modelOffset = 0)
    {

        $arrayNode = $this->prepareGetArrayNode($owner['document'], $this->configData['path']);
        $loaderOffset++;

        foreach($offsets as $key => $offset) {
            $this->method($owner['cachedModels'][$offset], 'unsetOwnerRelationship', null, array(), $modelOffset);
            $adjustedOffset = $offset - $key;
            $this->method($arrayNode, 'offsetUnset', null, array($adjustedOffset), $key);
            $this->method($owner['loader'], 'shiftCachedModels', null, array($adjustedOffset), $loaderOffset++);
        }
    }

    protected function offsetCreateTest($key, $count = 5)
    {
        $item = $this->getItem();
        $owner = $this->getOwner();
        $data = array('name' => 'pixie');

        $itemRepository = $this->getEmbeddedRepository();
        $this->setRepositories(array(
            $this->configData['itemModel'] => $itemRepository
        ));

        if($key <= $count) {
            $this->method($itemRepository, 'load', $item['model'], array($data), 0);
        }
        
        $this->prepareSetItem($owner, $item, $key, $count);
        $this->handler->offsetCreate($owner['model'], $this->propertyConfig, $key, $data);
    }

    protected function getPreloader() {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Preloader');
    }

    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Config');
    }

    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Side');
    }

    protected function getRelationship()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\EmbedsMany');
    }

    protected function getHandler()
    {
        return new \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Handler(
            $this->ormBuilder,
            $this->repositories,
            $this->planners,
            $this->plans,
            $this->steps,
            $this->loaders,
            $this->relationship,
            $this->groupMapper,
            $this->cascadeMapper
        );
    }
}
