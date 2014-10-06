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
        $oldOwner = $this->getOwner('many', $this->oldOwnerProperty);
        $item = $this->getItem($oldOwner);
        $owner = $this->getOwner();
        $this->prepareSetItem($owner, $item, 0);
        $this->handler->offsetSet($owner['model'], $this->propertyConfig, 0, $item['model']);
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
        $owner = $this->getOwner('many', null, true);
        $remove = array(
            $owner['cachedModels'][3],
            $owner['cachedModels'][1],
        );
        $loaderOffset = 1;
        $this->prepareUnsetItems($owner, array(1, 3), $loaderOffset);
        $this->handler->removeItems($owner['model'], $this->propertyConfig, $remove);
    }
    
    /**
     * @covers ::offsetCreate
     * @covers ::<protected>
     */
    public function testOffsetCreate() {
        $item = $this->getItem();
        $owner = $this->getOwner();
        $data = array('name' => 'pixie');
        
        $itemRepository = $this->getEmbeddedRepository();
        $this->setRepositories(array(
            $this->configData['itemModel'] => $itemRepository
        ));
        
        $this->method($itemRepository, 'load', $item['model'], array($data), 0);
        $this->prepareSetItem($owner, $item, 1);
        $this->handler->offsetCreate($owner['model'], $this->propertyConfig, 1, $data);
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
    
    protected function prepareSetItem($owner, $item, $key)
    {
        $this->method($owner['loader'], 'cacheModel', null, array($key, $item['model']), 0);
        $arrayNode = $this->prepareGetArrayNode($owner['document'], $this->configData['path']);
        $this->method($arrayNode, 'offsetSet', null, array($key, $item['document']), 0);
    }
    
    protected function prepareUnsetItems($owner, $offsets, &$loaderOffset)
    {
        
        $arrayNode = $this->prepareGetArrayNode($owner['document'], $this->configData['path']);
        $loaderOffset++;
        
        foreach($offsets as $key => $offset) {
            echo($offset);
            $this->method($owner['cachedModels'][$offset], 'unsetOwnerRelationship', null, array(), 0);
            $adjustedOffset = $offset - $key;
            $this->method($arrayNode, 'offsetUnset', null, array($adjustedOffset), $key);
            $this->method($owner['loader'], 'shiftCachedModels', null, array($adjustedOffset), $loaderOffset++);
        }
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