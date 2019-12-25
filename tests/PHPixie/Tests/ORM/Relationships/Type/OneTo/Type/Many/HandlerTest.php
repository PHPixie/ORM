<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type\Many;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Handler
 */
class HandlerTest extends \PHPixie\Tests\ORM\Relationships\Type\OneTo\HandlerTest
{
    protected $itemSide = 'items';
    protected $ownerPropertyName = 'ownerItemsProperty';
    protected $configOwnerProperty = 'flowers';

    
    /**
     * @covers ::loadOwnerProperty
     * @covers ::<protected>
     */
    public function testLoadOwnerProperty()
    {
        $this->loadOwnerPropertyTest(true);
        $this->loadOwnerPropertyTest(false);
    }
    
    /**
     * @covers ::loadItemsProperty
     * @covers ::<protected>
     */
    public function testLoadItemsProperty()
    {
        $side = $this->side('items', $this->configData);
        $owner = $this->getOwner(true, false, true);
        
        $preloadValue = $this->getOwnerPreloadValue();
        $this->method($this->relationship, 'ownerPreloadValue', $preloadValue, array(
            $this->configData['itemOwnerProperty'],
            $owner['entity']
        ), 0);
        
        $query = $this->getQuery();
        $this->prepareQuery($side, $query, $owner['entity']);
        
        $loader = $this->getReusableResultLoader();
        $this->method($query, 'find', $loader, array(array(
            $preloadValue
        )));
        
        $proxy = $this->getLoaderProxy('editable');
        $this->method($this->loaders, 'editableProxy', $proxy, array($loader), 0);
        
        $this->method($owner['property'], 'setValue', null, array($proxy), 0);
        
        $this->handler->loadItemsProperty($side, $owner['entity']);
    }
    
    
    /**
     * @covers ::unlinkPlan
     * @covers ::<protected>
     */
    public function testUnlinkPlan()
    {
        $config = $this->config($this->configData);
        $owners = $this->getDatabaseEntity();
        $items = $this->getDatabaseEntity();

        $plan = $this->prepareUnlinkTest(true, $owners, true, $items);
        $this->assertsame($plan, $this->handler->unlinkPlan($config, $owners, $items));
    }

    /**
     * @covers ::unlinkItemsPlan
     * @covers ::<protected>
     */
    public function testUnlinkItemsPlan()
    {
        $config = $this->config($this->configData);
        $items = $this->getDatabaseEntity();

        $plan = $this->prepareUnlinkTest(false, null, true, $items);
        $this->assertsame($plan, $this->handler->unlinkItemsPlan($config, $items));
    }

    /**
     * @covers ::unlinkOwnersPlan
     * @covers ::<protected>
     */
    public function testUnlinkOwnersPlan()
    {
        $config = $this->config($this->configData);
        $owners = $this->getDatabaseEntity();

        $plan = $this->prepareUnlinkTest(true, $owners, false, null);
        $this->assertsame($plan, $this->handler->unlinkOwnersPlan($config, $owners));
    }

    /**
     * @covers ::addOwnerItems
     * @covers ::<protected>
     */
    public function testAddOwnerItems()
    {
        $this->modifyOwnerSingleItemTest('add');
        $this->modifyOwnerSingleItemTest('add', true);
        
        $this->modifyOwnerItemsTest('add');
        $this->modifyOwnerItemsTest('add', true);
        
        $this->withOwnedItemTest('add');
        $this->withOwnedItemTest('add', true);
        $this->withOwnedItemTest('add', false, true);
        
    }

    /**
     * @covers ::removeOwnerItems
     * @covers ::<protected>
     */
    public function testRemoveOwnerItems()
    {
        $this->modifyOwnerSingleItemTest('remove');
        $this->modifyOwnerSingleItemTest('remove', true);
        
        $this->modifyOwnerItemsTest('remove');
        $this->modifyOwnerItemsTest('remove', true);
        
        $this->withOwnedItemTest('remove');
        $this->withOwnedItemTest('remove', true);
        $this->withOwnedItemTest('remove', false, true);
        
    }

    /**
     * @covers ::resetProperties
     * @covers ::<protected>
     */
    public function testResetProperties()
    {
        $this->resetPropertiesTest('owner');
        $this->resetPropertiesTest('item');
    }

    /**
     * @covers ::removeItemOwner
     * @covers ::<protected>
     */
    public function testRemoveItemOwner()
    {
        $this->itemOwnerRemoveTest(false);
        $this->itemOwnerRemoveTest(true);
    }

    /**
     * @covers ::removeAllOwnerItems
     * @covers ::<protected>
     */
    public function testRemoveAllOwnerItems()
    {
        $this->removeAllOwnerItemsTest(false);
        $this->removeAllOwnerItemsTest(true);
    }
    
    /**
     * @covers ::mapPreload
     * @covers ::<protected>
     */
    public function testMapPreloadOwner()
    {
        $side = $this->side('owner', $this->configData);
        $result = $this->getReusableResult();
        $plan = $this->getPlan();
        $preloadProperty = $this->getOwnerPreloadValue();
        $relatedLoader = $this->getLoader();

        $owner = $this->getDatabaseEntity();
        $this->method($preloadProperty, 'owner', $owner, array(), 0);
        
        $preloader = $this->getOwnerPropertyPreloader();
        $this->method($this->relationship, 'ownerPropertyPreloader', $preloader, array($owner), 0);
        
        $this->assertEquals($preloader, $this->handler->mapPreload(
            $side,
            $preloadProperty,
            $result,
            $plan,
            $relatedLoader
        ));
    }
    
    protected function loadOwnerPropertyTest($isNull = false)
    {
        $side = $this->side('owner', $this->configData);
        $item = $this->getItem(true);
        
        if($isNull) {
            $this->prepareLoadSingleProperty($side, $item['entity'], null);
            $this->expectSetValue($item, null);
            
        }else {
            $owner  = $this->getOwner(false);
            $this->prepareLoadSingleProperty($side, $item['entity'], $owner['entity']);

            $this->expectSetValue($item, $owner);
        }
        
        $this->handler->loadOwnerProperty($side, $item['entity']);
    }
    
    protected function removeAllOwnerItemsTest($hasLoadedProperty = false)
    {
        $owner = $this->getOwner(true, $hasLoadedProperty);
        if($hasLoadedProperty) {
            $items = array(
                $this->getItem(true, true, true, $owner),
                $this->getItem(true, true, true, $owner),
            );

            $itemEntities = array();
            foreach($items as $item) {
                $itemEntities[]= $item['entity'];
                $this->expectSetValue($item, null);
            }

            $this->method($owner['loader'], 'accessedEntities', $itemEntities, array(), 0);
            $this->method($owner['loader'], 'removeAll', null, array(), 1);

            $this->expectsExactly($owner['loader'], 'remove', 0);
        }

        $this->handler->removeAllOwnerItems($this->propertyConfig, $owner['entity']);

    }

    protected function itemOwnerRemoveTest($ownerLoaded = false)
    {
        $owner = $this->getOwner();
        $item  = $this->getItem(true, true, $ownerLoaded, $owner);
        if($ownerLoaded) {
            $this->expectItemsModified($owner, 'remove', array($item));
        }

        $this->expectSetValue($item, null);
        $this->handler->removeItemOwner($this->propertyConfig, $item['entity']);
    }

    protected function resetPropertiesTest($type)
    {
        $owner = $this->getOwner();
        $item  = $this->getItem(false, true, true, $owner);
        $query = $this->getDatabaseQuery();

        $withoutProperty = $this->getDatabaseEntity();
        $this->method($withoutProperty, 'getRelationshipProperty', null, array($this->sidePropertyName($type), false), 0);


        $param = $type === 'owner' ? $owner : $item;
        $this->expectsExactly($param['property'], 'reset', 1);

        $side = $this->side($type, $this->configData);
        $this->handler->resetProperties($side, array($param['entity'], $withoutProperty, $this->getQuery()));
    }

    protected function modifyOwnerSingleItemTest($action = 'add', $ownerIsQuery = false)
    {
        $owner = $ownerIsQuery ? $this->getQuery() : $this->getOwner();
        
        $item  = $this->getItem($this->ifCreateMissingProperty($action, $ownerIsQuery));

        if(!$ownerIsQuery) {
            $this->expectItemsModified($owner, $action, array($item));
            $ownerParam = $owner['entity'];
        }else{
            $ownerParam = $owner;
        }

        if($action === 'add' && $ownerIsQuery) {
            $this->expectsExactly($item['property'], 'reset', 1);
        }else{
            $this->expectSetValue($item, $action === 'add' ? $owner : null);
        }

        $method = $action.'OwnerItems';
        $this->handler->$method($this->propertyConfig, $ownerParam, $item['entity']);
    }

    protected function modifyOwnerItemsTest($action = 'add', $withQuery = false)
    {
        $owner = $this->getOwner();

        $items = array(
            $this->getItem(),
            $this->getItem()
        );

        $query = $this->getQuery();

        if($withQuery) {
            $this->expectsExactly($owner['property'], 'reset', 1);
        }else{
            $this->expectItemsModified($owner, $action, array($items[0], $items[1]));
        }

        foreach($items as $item) {
            $this->expectSetValue($item, $action === 'add' ? $owner : null);
        }

        $itemParams = array();
        foreach($items as $item)
            $itemParams[] = $item['entity'];

        if($withQuery)
            $itemParams[] = $query;

        $method = $action.'OwnerItems';
        $this->handler->$method($this->propertyConfig, $owner['entity'], $itemParams);
    }
    
    protected function withOwnedItemTest($action = 'add', $ownerIsQuery = false, $sameId = false)
    {
        $itemOwner = $this->getOwner(true, true, false, 1);
        
        $item  = $this->getItem($this->ifCreateMissingProperty($action, $ownerIsQuery), true, true, $itemOwner);

        $owner = $ownerIsQuery ? $this->getQuery() : $this->getOwner(true, true, false, $sameId ? 1 : 2);

        if(!$ownerIsQuery) {
            $this->expectItemsModified($owner, $action, array($item));
            $ownerParam = $owner['entity'];
        }else{

            $ownerParam = $owner;
        }

        if($action === 'add' && $ownerIsQuery) {
            $this->expectsExactly($item['property'], 'reset', 1);
        }else{
            $this->expectSetValue($item, $action === 'add' && !$ownerIsQuery ? $owner : null);
        }

        if(!($action === 'add' && $sameId))
            $this->expectItemsModified($itemOwner, 'remove', array($item));

        $method = $action.'OwnerItems';
        $this->handler->$method($this->propertyConfig, $ownerParam, $item['entity']);
    }

    
    protected function ifCreateMissingProperty($action, $ownerIsQuery) {
        return !($action === 'reset' || ($action === 'add' && $ownerIsQuery));
    }
    
    protected function expectItemsModified($ownerMock, $method, $itemMocks, $expectNotCalled = false)
    {
        $items = array();
        foreach($itemMocks as $itemMock)
            $items[]= $itemMock['entity'];

        $method = $ownerMock['loader']
            ->expects($this->exactly($expectNotCalled ? 0 :1))
            ->method($method);

        if($method === 'removeAll') {
            $method->with();

        }else{
            $method->with($this->identicalTo($items));
        }
    }

    protected function expectsExactly($mock, $method, $exactly)
    {
        $mock
            ->expects($this->exactly($exactly))
            ->method($method)
            ->with();
    }



    protected function expectSetValue($itemMock, $ownerMock = null)
    {
        $owner = null;
        if($ownerMock !== null)
            $owner = $ownerMock['entity'];
        $itemMock['property']
            ->expects($this->once())
            ->method('setValue')
            ->with($this->identicalTo($owner));
    }

    protected function getItem($expectCreateMissing = true, $hasProperty = true, $ownerLoaded = false, $owner = null) {
        $ownerEntity = $owner !== null ? $owner['entity'] : null;
        $entity = $this->getDatabaseEntity();
        return $this->addSingleProperty($entity, 'owner', $hasProperty, $ownerLoaded, $ownerEntity, $expectCreateMissing);
    }

    protected function getOwner($hasProperty = true, $loaded = true, $expectCreateMissing = false, $id = 1)
    {
        $entity = $this->getDatabaseEntity();
        $property = null;
        $loader = null;

        $this->method($entity, 'id', $id, array());
        if($hasProperty) {
            $property = $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Entity\Items');
            $this->method($property, 'isLoaded', $loaded, array());

            if($loaded){
                $loader = $this->quickMock('\PHPixie\ORM\Loaders\Loader\Proxy\Editable');
                $this->method($property, 'value', $loader, array());
            }
        }

        $propertyName = $this->opposingPropertyName('item');
        $this->method($entity, 'getRelationshipProperty', $property, array($propertyName, $expectCreateMissing), null, true);
        
        return array(
            'entity'   => $entity,
            'property' => $property,
            'loader'   => $loader
        );
    }

    protected function getSingleProperty($type)
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Entity\Owner');
    }

    protected function getPreloader($type)
    {
        if($type !== 'owner')
            $type = 'items';

        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\\'.ucfirst($type));
    }
                     
    protected function getOwnerPreloadValue()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Value\Preload\Owner');
    }
                     
    protected function getOwnerPropertyPreloader()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Property\Owner');
    }

    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side\Config');
    }

    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side');
    }

    protected function getRelationship()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many');
    }

    protected function getHandler()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Handler(
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
