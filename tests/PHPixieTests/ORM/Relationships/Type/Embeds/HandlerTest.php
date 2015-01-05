<?php

namespace PHPixieTests\ORM\Relationships\Type\Embeds;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Handler
 */
abstract class HandlerTest extends \PHPixieTests\ORM\Relationships\Relationship\Implementation\Handler\EmbeddedTest
{
    protected $ownerPropertyName;
    protected $propertyConfig;
    protected $configOnwerProperty;
    protected $oldOwnerProperty = 'plants';
    protected $itemSideName;

    public function setUp()
    {
        $this->configData = array(
            'ownerModel'        => 'fairy',
            'itemModel'         => 'flower',
            'path'              => 'favorites.'.$this->configOwnerProperty,
            $this->ownerPropertyName => $this->configOwnerProperty,
        );

        $this->propertyConfig = $this->config($this->configData);
        parent::setUp();
    }

    protected function prepareRemoveItemFromOwner($item, $owner, &$propertyOffset = 0)
    {
        $params = array();
        if($owner['property'] instanceof \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Property) {
            $params[]= $item['entity'];
        }
        $this->method($owner['property'], 'remove', null, $params, $propertyOffset++);
    }
    
    /**
     * @covers ::mapDatabaseQuery
     * @covers ::<protected>
     */
    public function testMapDatabaseQuery ( )
    {
        $query = $this->getDatabaseDocumentQuery();
        $side = $this->side('item', $this->configData);
        $collection = $this->getCollectionCondition('or', true, array(5));
        $plan = $this->getPlan();

        $this->prepareMapConditionBuilder($query, $side, $collection, $plan);
        $this->handler->mapDatabaseQuery($query, $side, $collection, $plan);
    }
    
    /**
     * @covers ::mapEmbeddedContainer
     * @covers ::<protected>
     */
    public function testMapEmbeddedContainer ( )
    {
        $container = $this->getDocumentConditionContainer();
        $side = $this->side('item', $this->configData);
        $collection = $this->getCollectionCondition('or', true, array(5));
        $plan = $this->getPlan();

        $this->prepareMapConditionBuilder($container, $side, $collection, $plan);
        $this->handler->mapEmbeddedContainer($container, $side, $collection, $plan);
    }
    
    

    /**
     * @covers ::mapPreload
     * @covers ::<protected>
     */
    public function testMapPreload ( )
    {
        $side = $this->side('item', $this->configData);
        $preloadProperty = $this->preloadPropertyValue();
        $result = $this->getReusableResult();
        $plan = $this->getPlan();
        
        $preloadResult = $this->getPreloadResult();
        $this->method($this->relationship, 'preloadResult', $preloadResult, array($result, $this->configData['path']), 0);
        
        $preloader = $this->getPreloader();
        $this->method($this->relationship, 'preloader', $preloader, array(), 1);
        
        $this->method($this->mapperMocks['preload'], 'map', null, array(
            $preloader,
            $this->configData['itemModel'],
            $preloadProperty['preload'],
            $preloadResult,
            $plan
        ), 0);
        
        $this->assertSame($preloader, $this->handler->mapPreload($side, $preloadProperty['property'], $result, $plan));
    }

    protected function getItem($owner = null)
    {
        $item = $this->getRelationshipEntity('item');

        if($owner === null){
            $this->method($item['entity'], 'ownerPropertyName', null, array());
            $this->method($item['entity'], 'owner', null, array());
        }else{
            $this->method($item['entity'], 'ownerPropertyName', $this->oldOwnerProperty, array());
            $this->method($item['entity'], 'owner', $owner['entity'], array());
        }
        return $item;
    }

    protected function getRelationshipEntity($type)
    {
        $entity = $this->getEmbeddedEntity();
        $this->method($entity, 'modelName', $this->configData[$type.'Model'], array());
        $data = $this->getData();
        $document = $this->getDocument();

        $this->method($entity, 'data', $data, array());
        $this->method($data, 'document', $document, array());
        return array(
            'entity' => $entity,
            'data'  => $data,
            'document' => $document
        );
    }

    protected function prepareWrongItem()
    {
        $entity = $this->getEmbeddedEntity();
        $this->method($entity, 'modelName', 'nope', array());
        $this->setExpectedException('\PHPixie\ORM\Exception\Relationship');
        return $entity;
    }

    protected function getArrayNodeLoader()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\Embedded\ArrayNode');
    }
    
    abstract protected function prepareMapConditionBuilder($builder, $side, $collection, $plan);
    abstract protected function getPreloadResult();
    abstract protected function getPreloader();
}
