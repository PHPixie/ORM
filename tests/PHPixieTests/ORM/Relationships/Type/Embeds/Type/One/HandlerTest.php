<?php

namespace PHPixieTests\ORM\Relationships\Type\Embeds\Type\One;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\One\Handler
 */
class HandlerTest extends \PHPixieTests\ORM\Relationships\Type\Embeds\HandlerTest
{
    protected $ownerPropertyName = 'ownerItemProperty';
    protected $configOwnerProperty = 'flower';

    /**
     * @covers ::setItem
     * @covers ::<protected>
     */
    public function testSetItem()
    {
        $this->setItemTest();
        $this->setItemTest(true);
    }

    /**
     * @covers ::offsetSet
     * @covers ::<protected>
     */
    public function testOffsetSetWrongModel() {
        $owner = $this->getOwner();
        $item = $this->prepareWrongItem();
        $this->handler->setItem($owner['model'], $this->propertyConfig, $item);
    }

    /**
     * @covers ::setItem
     * @covers ::<protected>
     */
    public function testRemoveItem()
    {
        $this->removeItemTest();
        $this->removeItemTest(true);
    }

    /**
     * @covers ::createItem
     * @covers ::<protected>
     */
    public function testCreateItem()
    {
        $itemRepository = $this->prepareItemRepository();

        $this->createItemTest($itemRepository);
        $this->createItemTest($itemRepository, true);
    }

    /**
     * @covers ::loadProperty
     * @covers ::<protected>
     */
    public function testLoadProperty()
    {
        $itemRepository = $this->prepareItemRepository();
        $owner = $this->getOwner();
        $item = $this->getItem();

        $document = $this->prepareGetDocument($owner['document'], $this->configData['path']);
        $this->method($itemRepository, 'loadFromDocument', $item['model'], array($document), 0);
        $this->method($item['model'], 'setOwnerRelationship',null , array($owner['model'], $this->configData['ownerItemProperty']), 0);
        $this->handler->loadProperty($this->propertyConfig, $owner['model']);
    }

    /**
     * @covers ::mapRelationshipBuilder
     * @covers ::<protected>
     */
    public function testMapRelationshipBuilder()
    {
        $this->mapRelationshipBuilderTest();
        $this->mapRelationshipBuilderTest('test');
    }

    protected function setItemTest($ownerLoaded = false)
    {
        $owner = $this->getOwner(null, $ownerLoaded);
        if($ownerLoaded) {
            $this->method($owner['item']['model'], 'unsetOwnerRelationship', null, array(), 0);
        }
        $item = $this->getItem();
        $this->prepareSetItemModel($owner, $item);
        $this->handler->setItem($owner['model'], $this->propertyConfig, $item['model']);
    }

    protected function removeItemTest($ownerLoaded = false)
    {
        $owner = $this->getOwner(null, $ownerLoaded);
        if($ownerLoaded) {
            $this->method($owner['item']['model'], 'unsetOwnerRelationship', null, array(), 0);
        }
        $this->prepareRemoveItem($owner);
        $this->handler->removeItem($owner['model'], $this->propertyConfig);
    }

    protected function createItemTest($itemRepository, $ownerLoaded = false)
    {

        $item = $this->getItem();
        $owner = $this->getOwner();
        $data = array('name' => 'pixie');

        $this->method($itemRepository, 'load', $item['model'], array(), 0);
        $this->prepareSetItemModel($owner, $item, 1);
        $this->handler->createItem($owner['model'], $this->propertyConfig, $data);
    }


    protected function prepareSetItemModel($owner, $item, $itemOffset = 2)
    {
        list($document, $key) = $this->prepareGetParentDocumentAndKey($owner['document'], $this->configData['path']);
        $this->method($document, 'set', null, array($key, $item['document']), 0);
        $this->method($item['model'], 'setOwnerRelationship',null , array($owner['model'], $this->configData['ownerItemProperty']), $itemOffset);
        $this->preparePopertySetValue($owner['property'], $item['model']);
    }

    protected function prepareRemoveItem($owner)
    {
        list($document, $key) = $this->prepareGetParentDocumentAndKey($owner['document'], $this->configData['path']);
        $this->method($document, 'remove', null, array($key), 0);
        $this->preparePopertySetValue($owner['property'], null);
    }

    protected function prepareItemRepository()
    {
        $itemRepository = $this->getEmbeddedRepository();
        $this->setRepositories(array(
            $this->configData['itemModel'] => $itemRepository
        ));
        return $itemRepository;
    }

    protected function preparePopertySetValue($property, $value)
    {
        $property
            ->expects($this->once())
            ->method('setValue')
            ->with($value);
    }

    protected function getOwner($propertyName = null, $loaded = false)
    {
        if($propertyName == null) {
            $propertyName = $this->configOwnerProperty;
        }

        $owner = $this->getRelationshipModel('owner');
        $property = $this->getProperty();
        $itemModel = null;
        if($loaded) {
            $owner['item'] = $this->getItem($owner);
            $itemModel = $owner['item']['model'];
        }
        $this->method($property, 'isLoaded', $loaded, array());
        $this->method($property, 'value', $itemModel, array());

        $this->method($owner['model'], 'relationshipProperty', $property, array($propertyName), null, true);
        $owner['property'] = $property;
        return $owner;
    }

    protected function mapRelationshipBuilderTest($pathPrefix = null)
    {
        $builder = $this->quickMock('\PHPixie\Database\Conditions\Builder');

        $side = $this->side('item', $this->configData);
        $group = $this->getConditionGroup('or', true, array(5));
        $plan = $this->getPlan();

        $this->prepareMapRelationshipBuilder($side, $builder, $group, $plan, $pathPrefix);
        $this->handler->mapRelationshipBuilder($side, $builder, $group, $plan, $pathPrefix);
    }

    protected function prepareMapRelationshipBuilder($side, $builder, $group, $plan, $pathPrefix)
    {
        if($pathPrefix === null) {
            $pathPrefix = $this->configData['path'];
        }else{
            $pathPrefix = $pathPrefix.'.'.$this->configData['path'];
        }
        $this->method($this->embeddedGroupMapper, 'mapConditionGroup', null, array($this->configData['itemModel'], $builder, $group, $plan, $pathPrefix), 0);
    }

    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\One\Property\Model\Item');
    }

    protected function getPreloader() {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Preloader');
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
        return new \PHPixie\ORM\Relationships\Type\Embeds\Type\One\Handler(
            $this->ormBuilder,
            $this->repositories,
            $this->planners,
            $this->plans,
            $this->steps,
            $this->loaders,
            $this->relationship,
            $this->groupMapper,
            $this->cascadeMapper,
            $this->embeddedGroupMapper
        );
    }
}
