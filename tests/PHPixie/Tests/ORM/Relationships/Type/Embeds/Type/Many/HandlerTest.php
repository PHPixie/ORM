<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds\Type\Many;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Handler
 */
class HandlerTest extends \PHPixie\Tests\ORM\Relationships\Type\Embeds\HandlerTest
{
    protected $ownerPropertyName = 'ownerItemsProperty';
    protected $configOwnerProperty = 'flowers';

    /**
     * @covers ::offsetSet
     * @covers ::<protected>
     */
    public function testOffsetSet()
    {
        $this->offsetSetTest(1);
        $this->offsetSetTest(1, 5, false, true);
        $this->offsetSetTest(5, 5);
        $this->offsetSetTest(1, 5, true, false);
        $this->offsetSetTest(1, 5, true, true, 'one');
        $this->offsetSetTest(null);
    }
    
    /**
     * @covers ::offsetSet
     * @covers ::<protected>
     */
    public function testWrongOffset()
    {
        $this->offsetSetTest(6, 5);
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
        $owner = $this->getOwner(true);
        $loaderOffset = 0;
        $this->prepareUnsetItems($owner, array(1), 1, $loaderOffset);
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
        $owner = $this->getOwner(true);
        foreach($owner['cachedEntities'] as $item) {
            $this->method($item, 'unsetOwnerRelationship', null, array(), 0);
        }
        $this->method($owner['loader'], 'clearCachedEntities', null, array(), 1);
        $this->method($owner['node'], 'clear', null, array(), 0);
        $this->prepareCheckArrayNode($owner, 0, false, 1);
        $this->handler->removeAllItems($owner['entity'], $this->propertyConfig);
    }

    /**
     * @covers ::loadProperty
     * @covers ::<protected>
     */
    public function testLoadProperty()
    {
        $this->loadPropertyTest(false);
        //$this->loadPropertyTest(true);
    }
    
    protected function loadPropertyTest($isEmpty)
    {
        $owner = $this->getOwner();
        
        $count = $isEmpty ? 0 : 5;
        $arrayNode = $this->prepareCheckArrayNode($owner, $count, true);
        
        $this->method($this->loaders, 'arrayNode', $owner['loader'], array(
            $this->configData['itemModel'],
            $arrayNode,
            $owner['entity'],
            $this->configData['ownerItemsProperty'],
        ), 0);
        
        $this->method($owner['property'], 'setValue', null, array($owner['loader']), 0);
        $this->handler->loadProperty($this->propertyConfig, $owner['entity']);    
    }

    protected function prepareMapConditionBuilder($builder, $side, $collection, $plan)
    {
        $this->method($builder, 'startConditionGroup', null, array(
            $collection->logic(),
            $collection->isNegated()
        ), 0);
        
        $this->method($builder, 'andNot', null, array(
            $this->configData['path'],
            null
        ), 1);
            
        $container = $this->getDocumentConditionContainer();
        
        $this->method($builder, 'addSubarrayItemPlaceholder', $container, array(
            $this->configData['path']
        ), 2);
        
        $this->method($this->mapperMocks['conditions'], 'map', null, array(
            $container,
            $this->configData['itemModel'],
            $collection->conditions(),
            $plan
        ), 0);
        
        $this->method($builder, 'endGroup', null, array(), 3);
        
    }

    protected function offsetSetTest($key, $count = 5, $withOldItem = false, $withOldOwner = false, $withOldOwnerType = 'many')
    {
        $oldOwner = null;
        if($withOldOwner) {
            $oldOwner = $this->getOldOwner();
        }

        $item = $this->getItem($oldOwner);
        $this->prepareRemoveItemFromOwner($item, $withOldOwnerType);
        
        $owner = $this->getOwner($withOldItem);
        $this->prepareSetItem($owner, $item, $key, $count, $withOldItem);
        $this->handler->offsetSet($owner['entity'], $this->propertyConfig, $key, $item['entity']);
    }

    protected function prepareSetItem($owner, $item, $key, $count, $withOldItem = false)
    {
        if($key === null) {
            $key = $count;
        }
        
        $loaderOffset = 0;
        $this->method($owner['loader'], 'count', $count, array(), $loaderOffset++);

        if($key <= $count) {
            if($key < $count) {
                $cachedEntity = $withOldItem ? $owner['cachedEntities'][$key] :null;
                $this->method($owner['loader'], 'getCachedEntity', $cachedEntity, array($key), $loaderOffset++);
                if($withOldItem)
                    $this->method($owner['cachedEntities'][$key], 'unsetOwnerRelationship', null, array(), 0);
            }
            $this->method($owner['loader'], 'cacheEntity', null, array($key, $item['entity']), $loaderOffset);
            $this->method($owner['node'], 'offsetSet', null, array($key, $item['document']), 0);
            $this->prepareCheckArrayNode($owner, $count, false, 1);
        }else{
            $this->setExpectedException('\PHPixie\ORM\Exception\Relationship');
        }
    }

    protected function removeItemsTest($keys)
    {
        $owner = $this->getOwner(true);

        $remove = array();
        foreach($keys as $key) {
            $remove[] = $owner['cachedEntities'][$key];
        }

        if(count($remove) == 1) {
            $remove = current($remove);
        }

        $loaderOffset = 1;
        $this->prepareUnsetItems($owner, $keys, 1, $loaderOffset, 1);
        $this->handler->removeItems($owner['entity'], $this->propertyConfig, $remove);
    }

    protected function prepareUnsetItems($owner, $offsets, $countRemaining, &$loaderOffset, $entityOffset = 0)
    {
        $loaderOffset+=2;

        foreach($offsets as $key => $offset) {
            $this->method($owner['cachedEntities'][$offset], 'unsetOwnerRelationship', null, array(), $entityOffset);
            $adjustedOffset = $offset - $key;
            $this->method($owner['node'], 'offsetUnset', null, array($adjustedOffset), $key);
            $this->method($owner['loader'], 'shiftCachedEntities', null, array($adjustedOffset), $loaderOffset++);
        }
        
        $this->prepareCheckArrayNode($owner, $countRemaining, false, $key+1);
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
    
    protected function prepareCheckArrayNode($owner, $count, $createNode = false, $nodeOffset = 0)
    {
        list($parent, $key) = $this->prepareGetParentDocumentAndKey($owner['document'], $this->configData['path'], true);
        
        if($createNode) {
            $arrayNode = $this->prepareGetArrayNode($parent, $key, true, 1);
        }else{
            $arrayNode = $owner['node'];
        }
        
        $this->method($arrayNode, 'count', $count, array(), $nodeOffset);
        
        if($count === 0) {
            $this->method($parent, 'remove', null, array($key), 0);
            
        }else{
            $this->method($parent, 'set', null, array($key, $arrayNode), 0);
        }
        
        return $arrayNode;
    }
    
    protected function getOwner($addCachedEntities = false)
    {
        $owner = $this->getRelationshipEntity('owner');
        $property = $this->getProperty();
        
        $loader = $this->getArrayNodeLoader();
        $arrayNode = $this->getArrayNode();
        $this->method($loader, 'arrayNode', $arrayNode, array());
        $this->method($property, 'value', $loader, array());
        
        $owner['loader'] = $loader;
        $owner['node'] = $arrayNode;
        
        if($addCachedEntities) {
            $cached = array();
            for($i=0; $i<5; $i++) {
                $item = $this->getItem();
                $cached[]=$item['entity'];
            }
            $owner['cachedEntities'] = $cached;
            $this->method($owner['loader'], 'getCachedEntities', $cached, array());
        }
        $this->method($owner['entity'], 'getRelationshipProperty', $property, array($this->configOwnerProperty), null, true);
        $owner['property'] = $property;
        
        return $owner;
    }

    protected function getProperty()
    {
        return $this->getEmbedsManyProperty();
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
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many');
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
