<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Handler
 */
abstract class HandlerTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\HandlerTest
{
    protected $planners;
    protected $ownerPropertyName;
    protected $propertyConfig;
    protected $configOwnerProperty;
    protected $oldOwnerProperty = 'plants';
    protected $itemSideName;
    
    protected $documentPlanner;

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
        
        $this->documentPlanner = $this->getPlanner('document');
        $this->method($this->planners, 'document', $this->documentPlanner, array());
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
        $relatedLoader = $this->getLoader();
        
        $preloadResult = $this->getPreloadResult();
        $this->method($this->relationship, 'preloadResult', $preloadResult, array($result, $this->configData['path']), 0);
        
        $preloader = $this->getPreloader();
        $this->method($this->relationship, 'preloader', $preloader, array(), 1);
        
        $this->method($this->mapperMocks['preload'], 'map', null, array(
            $preloader,
            $this->configData['itemModel'],
            $preloadProperty['preload'],
            $preloadResult,
            $plan,
            $relatedLoader
        ), 0);
        
        $this->assertSame($preloader, $this->handler->mapPreload($side, $preloadProperty['property'], $result, $plan, $relatedLoader));
    }
    
    protected function prepareGetDocument($document, $path, $createMissing = false, $at = 0)
    {
        $subdocument = $this->getDocument();
        $this->method(
            $this->documentPlanner,
            'getDocument',
            $subdocument,
            array($document, $path, $createMissing),
            $at
        );
        return $subdocument;
    }
    
    protected function prepareGetArrayNode($document, $path, $createMissing = false, $at = 0)
    {
        $arrayNode = $this->getArrayNode();
        $this->method(
            $this->documentPlanner,
            'getArrayNode',
            $arrayNode,
            array($document, $path, $createMissing),
            $at
        );
        return $arrayNode;
    }
    
    protected function prepareGetParentDocumentAndKey($document, $path, $createMissing = false, $parentKey = 'parentKey', $at = 0)
    {
        $parent = $this->getDocument();
        $this->method(
            $this->documentPlanner,
            'getParentDocumentAndKey',
            array($parent, $parentKey),
            array($document, $path, $createMissing),
            $at
        );
        return array($parent, $parentKey);
    }
    
    protected function prepareRemoveItemFromOwner($item, $ownerRelationshipType = 'one')
    {
        if($item['owner'] === null)
            return;
        
        if($ownerRelationshipType == 'many') {
            $property = $this->getEmbedsOneProperty();
            $this->method($property, 'remove', null, array(), 0);
        }else{
            $property = $this->getEmbedsManyProperty();
            $this->method($property, 'remove', null, array($item['entity']), 0);
        }
        
        $this->method($item['owner']['entity'], 'getRelationshipProperty', $property, array($this->oldOwnerProperty), null, true);
    }
    
    protected function getOldOwner()
    {
        return array(
            'entity' => $this->getEmbeddedEntity()
        );
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
        
        $item['owner'] = $owner;
        
        return $item;
    }

    protected function getRelationshipEntity($type)
    {
        $entity = $this->getEmbeddedEntity();
        $this->method($entity, 'modelName', $this->configData[$type.'Model'], array());
        $data = $this->getDocumentData();
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

    protected function getEmbeddedEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Embedded\Entity');
    }
    
    protected function getDocumentData()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Document');
    }
    
    protected function getDocument()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\Document');
    }
    
    protected function getArrayNode()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\ArrayNode');
    }
    
    protected function getArrayNodeLoader()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Embedded\ArrayNode');
    }
    
    protected function getEmbedsManyProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Property\Entity\Items');
    }
    
    protected function getEmbedsOneProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\One\Property\Entity\Item');
    }
    
    protected function getDatabaseDocumentQuery()
    {
        return $this->abstractMock('\PHPixie\Database\Type\Document\Query\Items');
    }
    
    protected function getDocumentConditionContainer()
    {
        return $this->quickMock('\PHPixie\Database\Type\Document\Conditions\Builder\Container');
    }
    
    abstract protected function prepareMapConditionBuilder($builder, $side, $collection, $plan);
    abstract protected function getPreloadResult();
    abstract protected function getPreloader();
}
