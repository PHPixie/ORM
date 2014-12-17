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
        $owner = $this->getOwner(null, true);
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
        $owner = $this->getOwner(null, true);
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

    /**
     * @covers ::mapRelationshipBuilder
     * @covers ::<protected>
     */
    public function testMapRelationshipBuilder()
    {
        $builder = $this->quickMock('\PHPixie\Database\Conditions\Builder');

        $side = $this->side('item', $this->configData);
        $group = $this->getConditionGroup('or', true, array(5));
        $plan = $this->getPlan();

        $this->prepareMapRelationshipBuilder($side, $builder, $group, $plan, null);
        $this->handler->mapRelationshipBuilder($side, $builder, $group, $plan);
    }

    protected function prepareMapRelationshipBuilder($side, $builder, $group, $plan, $pathPrefix)
    {
        $subdocument = $this->quickMock('\PHPixie\Database\Document\Conditions\Subdocument');
        $this->method($this->ormBuilder, 'subdocumentCondition', $subdocument, array(), 0);
        $this->method($this->embeddedGroupMapper, 'mapConditions', null, array($this->configData['itemModel'], $subdocument, array(5), $plan), 0);
        $this->method($builder, 'addOperatorCondition', null, array('or', true, $this->configData['path'], 'elemMatch', $subdocument), 0);
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
        $this->handler->offsetSet($owner['model'], $this->propertyConfig, $key, $item['model']);
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
                $cachedModel = $withOldItem ? $owner['cachedModels'][$key] :null;
                $this->method($owner['loader'], 'getCachedModel', $cachedModel, array($key), $loaderOffset++);
                if($withOldItem)
                    $this->method($owner['cachedModels'][$offset], 'unsetOwnerRelationship', null, array(), 0);
            }
            $this->method($owner['loader'], 'cacheModel', null, array($key, $item['model']), $loaderOffset);
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

    protected function getOwner($propertyName = null, $addCachedModels = false)
    {
        if($propertyName == null) {
            $propertyName = $this->configOwnerProperty;
        }

        $owner = $this->getRelationshipModel('owner');
        $property = $this->getProperty();
        $loader = $this->getArrayNodeLoader();
        $this->method($property, 'value', $loader, array());
        $owner['loader'] = $loader;
        if($addCachedModels) {
            $cached = array();
            for($i=0; $i<5; $i++) {
                $item = $this->getItem();
                $cached[]=$item['model'];
            }
            $owner['cachedModels'] = $cached;
            $this->method($owner['loader'], 'cachedModels', $cached, array());
        }
        $this->method($owner['model'], 'relationshipProperty', $property, array($propertyName), null, true);
        $owner['property'] = $property;
        return $owner;
    }

    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Property');
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
        return new \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Handler(
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
