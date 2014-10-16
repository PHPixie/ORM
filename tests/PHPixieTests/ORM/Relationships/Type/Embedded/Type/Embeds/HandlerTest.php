<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Handler
 */
abstract class HandlerTest extends \PHPixieTests\ORM\Relationships\Type\Embedded\HandlerTest
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
            'path'              => 'favorites.'.$this->configOnwerProperty,
            $this->ownerPropertyName => $this->configOwnerProperty,
        );

        $this->propertyConfig = $this->config($this->configData);
        parent::setUp();
    }

    /**
     * @covers ::mapRelationship
     * @covers ::<protected>
     */
    public function testMapRelationship ( )
    {
        $query = $this->getDatabaseQuery();
        $builder = $this->quickMock('\PHPixie\Database\Conditions\Builder');
        $this->method($query, 'getWhereBuilder', $builder, array(), 0);

        $side = $this->side('item', $this->configData);
        $group = $this->getConditionGroup('or', true, array(5));
        $plan = $this->getPlan();

        $this->prepareMapRelationshipBuilder($side, $builder, $group, $plan, null);
        $this->handler->mapRelationship($side, $query, $group, $plan);
    }

    protected function prepareRemoveItemFromOwner($item, $owner, &$propertyOffset = 0)
    {
        $params = array();
        if($owner['property'] instanceof \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Property) {
            $params[]= $item['model'];
        }
        $this->method($owner['property'], 'remove', null, $params, $propertyOffset++);
    }

    protected function getItem($owner = null)
    {
        $item = $this->getRelationshipModel('item');

        if($owner === null){
            $this->method($item['model'], 'ownerPropertyName', null, array());
            $this->method($item['model'], 'owner', null, array());
        }else{
            $this->method($item['model'], 'ownerPropertyName', $this->oldOwnerProperty, array());
            $this->method($item['model'], 'owner', $owner['model'], array());
        }
        return $item;
    }

    protected function getOwner($relationshipType = 'many', $propertyName = null, $addCachedModels = false)
    {
        if($propertyName == null) {
            $propertyName = $this->configOwnerProperty;
        }

        $owner = $this->getRelationshipModel('owner');
        if($relationshipType === 'many') {
            $property = $this->getManyProperty();
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
        }else {
            $property = $this->getOneProperty();
        }
        $this->method($owner['model'], 'relationshipProperty', $property, array($propertyName), null, true);
        $owner['property'] = $property;
        return $owner;
    }

    protected function getRelationshipModel($type)
    {
        $model = $this->getEmbeddedModel();
        $this->method($model, 'modelName', $this->configData[$type.'Model'], array());
        $data = $this->getData();
        $document = $this->getDocument();

        $this->method($model, 'data', $data, array());
        $this->method($data, 'document', $document, array());
        return array(
            'model' => $model,
            'data'  => $data,
            'document' => $document
        );
    }

    protected function prepareWrongItem()
    {
        $model = $this->getEmbeddedModel();
        $this->method($model, 'modelName', 'nope', array());
        $this->setExpectedException('\PHPixie\ORM\Exception\Relationship');
        return $model;
    }

    protected function getArrayNodeLoader() {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\Embedded\ArrayNode');
    }


    protected function getDatabaseModel()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Database\Model');
    }

    protected function getEmbeddedModel()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Embedded\Model');
    }

    protected function getOneProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Property');
    }

    protected function getManyProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Property');
    }

    abstract protected function prepareMapRelationshipBuilder($side, $builder, $group, $plan, $pathPrefix);
    abstract protected function getPreloader();
}
