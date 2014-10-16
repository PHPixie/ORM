<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Handler
 */
class HandlerTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\HandlerTest
{
    protected $itemSide = 'items';
    protected $ownerPropertyName = 'ownerItemsProperty';
    protected $configOnwerProperty = 'flowers';

    /**
     * @covers ::loadOwnerProperty
     * @covers ::<protected>
     */
    public function testLoadOwnerProperty()
    {
        $side = $this->side('owner', $this->configData);
        $related = $this->getDatabaseModel();
        $owner = $this->prepareLoadSingleProperty($side, $related);
        $this->assertSame($owner, $this->handler->loadOwnerProperty($side, $related));
    }

    /**
     * @covers ::loadItemsProperty
     * @covers ::<protected>
     */
    public function testLoadItemsProperty()
    {
        $side = $this->side('item', $this->configData);
        $related = $this->getDatabaseModel();
        $loader = $this->quickMock();
        $query = $this->getQuery();

        $this->prepareQuery($side, $query, $related);
        $this->method($query, 'findOne', $model, array());
        $this->assertSame($owner, $this->handler->loadOwnerProperty($side, $related));
    }

    /**
     * @covers ::unlinkPlan
     * @covers ::<protected>
     */
    public function testUnlinkPlan()
    {
        $config = $this->config($this->configData);
        $owners = $this->getModel();
        $items = $this->getModel();

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
        $items = $this->getModel();

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
        $owners = $this->getModel();

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

    protected function removeAllOwnerItemsTest($hasLoadedProperty = false)
    {
        $owner = $this->getOwner(true, $hasLoadedProperty);
        if($hasLoadedProperty) {
            $items = array(
                $this->getItem(true, true, $owner),
                $this->getItem(true, true, $owner),
            );

            $itemModels = array();
            foreach($items as $item) {
                $itemModels[]= $item['model'];
                $this->expectSetValue($item, null);
            }

            $this->method($owner['loader'], 'accessedModels', $itemModels, array(), 0);
            $this->method($owner['loader'], 'removeAll', null, array(), 1);

            $this->expectsExactly($owner['loader'], 'remove', 0);
        }

        $this->handler->removeAllOwnerItems($this->propertyConfig, $owner['model']);

    }

    protected function itemOwnerRemoveTest($ownerLoaded = false)
    {
        $owner = $this->getOwner();
        $item  = $this->getItem(true, $ownerLoaded, $owner);
        if($ownerLoaded) {
            $this->expectItemsModified($owner, 'remove', array($item));
        }

        $this->expectSetValue($item, null);
        $this->handler->removeItemOwner($this->propertyConfig, $item['model']);
    }

    protected function resetPropertiesTest($type)
    {
        $owner = $this->getOwner();
        $item  = $this->getItem(true, true, $owner);
        $query = $this->getDatabaseQuery();

        $withoutProperty = $this->getDatabaseModel();
        $this->method($withoutProperty, 'relationshipProperty', null, array($this->propertyName($type), false), 0);


        $param = $type === 'owner' ? $owner : $item;
        $this->expectsExactly($param['property'], 'reset', 1);

        $side = $this->side($type, $this->configData);
        $this->handler->resetProperties($side, array($param['model'], $withoutProperty, $this->getQuery()));
    }

    protected function modifyOwnerSingleItemTest($action = 'add', $ownerIsQuery = false)
    {
        $owner = $ownerIsQuery ? $this->getQuery() : $this->getOwner();
        $item  = $this->getItem();

        if(!$ownerIsQuery) {
            $this->expectItemsModified($owner, $action, array($item));
            $ownerParam = $owner['model'];
        }else{
            $ownerParam = $owner;
        }

        if($action === 'add' && $ownerIsQuery) {
            $this->expectsExactly($item['property'], 'reset', 1);
        }else{
            $this->expectSetValue($item, $action === 'add' ? $owner : null);
        }

        $method = $action.'OwnerItems';
        $this->handler->$method($this->propertyConfig, $ownerParam, $item['model']);
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
            $itemParams[] = $item['model'];

        if($withQuery)
            $itemParams[] = $query;

        $method = $action.'OwnerItems';
        $this->handler->$method($this->propertyConfig, $owner['model'], $itemParams);
    }

    protected function withOwnedItemTest($action = 'add', $ownerIsQuery = false, $sameId = false)
    {
        $itemOwner = $this->getOwner(true, true, 1);
        $item  = $this->getItem(true, true, $itemOwner);

        $owner = $ownerIsQuery ? $this->getQuery() : $this->getOwner(true, true, $sameId ? 1 : 2);

        if(!$ownerIsQuery) {
            $this->expectItemsModified($owner, $action, array($item));
            $ownerParam = $owner['model'];
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
        $this->handler->$method($this->propertyConfig, $ownerParam, $item['model']);
    }

    protected function expectItemsModified($ownerMock, $method, $itemMocks, $expectNotCalled = false)
    {
        $items = array();
        foreach($itemMocks as $itemMock)
            $items[]= $itemMock['model'];

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
            $owner = $ownerMock['model'];
        $itemMock['property']
            ->expects($this->once())
            ->method('setValue')
            ->with($this->identicalTo($owner));
    }

    protected function getItem($hasProperty = true, $ownerLoaded = false, $owner = null) {
        $model = $this->getDatabaseModel();
        return $this->addSingleProperty($model, 'owner', $hasProperty, $ownerLoaded, $owner['model']);
    }

    protected function getOwner($hasProperty = true, $loaded = true, $id = 1)
    {
        $model = $this->getDatabaseModel();
        $property = null;
        $loader = null;

        $this->method($model, 'id', $id, array());
        if($hasProperty) {
            $property = $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model\Items');
            $this->method($property, 'isLoaded', $loaded, array());

            if($loaded){
                $loader = $this->quickMock('\PHPixie\ORM\Loaders\Loader\Proxy\Editable');
                $this->method($property, 'value', $loader, array());
            }
        }

        $propertyName = $this->propertyName('owner');
        $this->method($model, 'relationshipProperty', $property, array($propertyName), null, true);

        return array(
            'model'    => $model,
            'property' => $property,
            'loader'   => $loader
        );
    }

    protected function getSingleProperty($type)
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model\Owner');
    }

    protected function getPreloader($type)
    {
        if($type !== 'owner')
            $type = 'items';

        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\\'.ucfirst($type));
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
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneToMany');
    }

    protected function getHandler()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Handler(
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
