<?php

namespace PHPixieTests\ORM\Relationships\Type\Embeds\Type\Many;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Handler
 */
class HandlerTest extends \PHPixieTests\ORM\Relationships\Type\Embeds\HandlerTest
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
        $this->offsetSetTest(1, 5, false, true);
        $this->offsetSetTest(null);
    }

    /**
     * @covers ::offsetSet
     * @covers ::<protected>
     */
    public function testOffsetSetWrongEntity() {
        $owner = $this->getOwner();
        $item = $this->prepareWrongItem();
        $this->handler->offsetSet($owner['entity'], $this->propertyConfig, 1, $item);
    }

    /**
     * @covers ::offsetUnset
     * @covers ::<protected>
     */
    public function testOffsetUnset() {
        $owner = $this->getOwner(null, true);
        $loaderOffset = 0;
        $this->prepareUnsetItems($owner, array(1), $loaderOffset);
        $this->handler->offsetUnset($owner['entity'], $this->propertyConfig, 1);
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
    public function testOffsetRemoveWrongEntity() {
        $owner = $this->getOwner();
        $item = $this->prepareWrongItem();
        $this->handler->removeItems($owner['entity'], $this->propertyConfig, $item);
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
        $owner = $this->getOwner(null, true);
        foreach($owner['cachedEntities'] as $item) {
            $this->method($item, 'unsetOwnerRelationship', null, array(), 0);
        }
        $this->method($owner['loader'], 'clearCachedEntities', null, array(), 1);
        $arrayNode = $this->prepareGetArrayNode($owner['document'], $this->configData['path']);
        $this->method($arrayNode, 'clear', null, array(), 0);
        $this->handler->removeAllItems($owner['entity'], $this->propertyConfig);
    }

    /**
     * @covers ::loadProperty
     * @covers ::<protected>
     */
    public function testLoadProperty()
    {
        $owner = $this->getOwner();

        $arrayNode = $this->prepareGetArrayNode($owner['document'], $this->configData['path']);
        $loader = $this->getArrayNodeLoader();
        $this->method($this->loaders, 'arrayNode', $loader, array(
            $this->configData['itemModel'],
            $owner['entity'],
            $arrayNode
        ), 0);
        $this->assertSame($loader, $this->handler->loadProperty($this->propertyConfig, $owner['entity']));
    }

    protected function prepareMapConditionBuilder($builder, $side, $collection, $plan)
    {
        $container = $this->quickMock('\PHPixie\Database\Type\Document\Conditions\Builder\Container');
        
        $this->method($builder, 'addSubarrayItemPlaceholder', $container, array(
            $this->configData['path'],
            $collection->logic(),
            $collection->isNegated(),
        ), 0);
        
        $this->method($this->mapperMocks['conditions'], 'map', null, array(
            $container,
            $this->configData['itemModel'],
            $collection->conditions(),
            $plan
        ), 0);
        
    }

    protected function offsetSetTest($key, $count = 5, $withOldOwner = false, $withOldItem = false)
    {
        $oldOwner = null;
        if($withOldOwner) {
            $oldOwner = $this->getOwner($this->oldOwnerProperty);
        }

        $item = $this->getItem($oldOwner);
        $owner = $this->getOwner();
        $this->prepareSetItem($owner, $item, $key, $count);
        $this->handler->offsetSet($owner['entity'], $this->propertyConfig, $key, $item['entity']);
    }

    protected function prepareSetItem($owner, $item, $key, $count, $withOldItem = false)
    {
        if($key === null) {
            $key = $count;
        }

        $arrayNode = $this->prepareGetArrayNode($owner['document'], $this->configData['path']);
        $this->method($arrayNode, 'count', $count, array(), 0);

        if($key <= $count) {
            $loaderOffset = 0;
            if($key < $count) {
                $cachedEntity = $withOldItem ? $owner['cachedEntities'][$key] :null;
                $this->method($owner['loader'], 'getCachedEntity', $cachedEntity, array($key), $loaderOffset++);
                if($withOldItem)
                    $this->method($owner['cachedEntities'][$offset], 'unsetOwnerRelationship', null, array(), 0);
            }
            $this->method($owner['loader'], 'cacheEntity', null, array($key, $item['entity']), $loaderOffset);
            $this->method($arrayNode, 'offsetSet', null, array($key, $item['document']), 1);
        }else{
            $this->setExpectedException('\PHPixie\ORM\Exception\Relationship');
        }
    }

    protected function removeItemsTest($keys)
    {
        $owner = $this->getOwner(null, true);

        $remove = array();
        foreach($keys as $key) {
            $remove[] = $owner['cachedEntities'][$key];
        }

        if(count($remove) == 1) {
            $remove = current($remove);
        }

        $loaderOffset = 1;
        $this->prepareUnsetItems($owner, $keys, $loaderOffset, 1);
        $this->handler->removeItems($owner['entity'], $this->propertyConfig, $remove);
    }

    protected function prepareUnsetItems($owner, $offsets, &$loaderOffset, $entityOffset = 0)
    {

        $arrayNode = $this->prepareGetArrayNode($owner['document'], $this->configData['path']);
        $loaderOffset++;

        foreach($offsets as $key => $offset) {
            $this->method($owner['cachedEntities'][$offset], 'unsetOwnerRelationship', null, array(), $entityOffset);
            $adjustedOffset = $offset - $key;
            $this->method($arrayNode, 'offsetUnset', null, array($adjustedOffset), $key);
            $this->method($owner['loader'], 'shiftCachedEntities', null, array($adjustedOffset), $loaderOffset++);
        }
    }

    protected function offsetCreateTest($key, $count = 5)
    {
        $item = $this->getItem();
        $owner = $this->getOwner();
        $data = array('name' => 'pixie');

        if($key <= $count) {
            $this->method($this->modelMocks['embedded'], 'loadEntityFromData', $item['entity'], array(
                $this->configData['itemModel'],
                $data
            ), 0);
        }

        $this->prepareSetItem($owner, $item, $key, $count);
        $this->handler->offsetCreate($owner['entity'], $this->propertyConfig, $key, $data);
    }

    protected function getOwner($propertyName = null, $addCachedEntities = false)
    {
        if($propertyName == null) {
            $propertyName = $this->configOwnerProperty;
        }

        $owner = $this->getRelationshipEntity('owner');
        $property = $this->getProperty();
        $loader = $this->getArrayNodeLoader();
        $this->method($property, 'value', $loader, array());
        $owner['loader'] = $loader;
        if($addCachedEntities) {
            $cached = array();
            for($i=0; $i<5; $i++) {
                $item = $this->getItem();
                $cached[]=$item['entity'];
            }
            $owner['cachedEntities'] = $cached;
            $this->method($owner['loader'], 'cachedEntities', $cached, array());
        }
        $this->method($owner['entity'], 'getRelationshipProperty', $property, array($propertyName), null, true);
        $owner['property'] = $property;
        return $owner;
    }

    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Property\Entity\Items');
    }

    protected function getPreloader() {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Preloader');
    }
    
    protected function getPreloadResult()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Preload\Result');
    }

    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Config');
    }

    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Side');
    }

    protected function getRelationship()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\EmbedsMany');
    }

    protected function getHandler()
    {
        return new \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Handler(
            $this->models,
            $this->planners,
            $this->plans,
            $this->steps,
            $this->loaders,
            $this->mappers,
            $this->relationship
        );
    }
}
