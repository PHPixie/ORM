<?php

namespace PHPixieTests\ORM\Relationships\Type\Embeds;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Handler
 */
abstract class HandlerTest extends \PHPixieTests\ORM\Relationships\Relationship\HandlerTest
{
    protected $ownerPropertyName;
    protected $propertyConfig;
    protected $configOnwerProperty;
    protected $oldOwnerProperty = 'plants'
        
    public function setUp()
    {
        $this->configData = array(
            'ownerModel'        => 'fairy',
            'itemModel'         => 'flower',
            $this->ownerPropertyName => $this->configOnwerProperty,
        );
        
        $this->propertyConfig = $this->config($this->configData);
        parent::setUp();
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
            $this->method($item, 'ownerPropertyName', null, array());
            $this->method($item, 'owner', null, array());
        }else{
            $this->method($item, 'ownerPropertyName', $this->oldOwnerProperty, array());
            $this->method($item, 'owner', $owner['model'], array());
        }
    }

    protected function getOwner($relationshipType = 'many')
    {
        $owner = $this->getRelationshipModel('owner');
        if($relationshipType === 'many') {
            $property = $this->getManyProperty();
            $loader = $this->getArrayNodeLoader();
            $this->method($property, 'value', $loader, array());
            $owner['loader'] = $loader;
        }else {
            $property = $this->getOneProperty();
        }
        $this->method($owner['model'], 'relationshipProperty', $property, array($this->oldOwnerProperty));
        $owner['property'] = $property;
        return $owner;
    }
    
    protected function getRelationshipModel($type)
    {
        $model = $this->getEmbeddedModel();
        $this->method($model, 'modelName', $this->configData($type.'Model'), array());
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
    
    protected function getArrayNodeLoader() {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\Embedded\ArrayNode');
    }
    
    
    protected function getData()
    {
        return $this->abstractMock('\PHPixie\ORM\Data\Type\Document');
    }
    
    protected function getArrayNode()
    {
        return $this->abstractMock('\PHPixie\ORM\Data\Type\Document\Node\ArrayNode');
    }
    
    protected function getDocument()
    {
        return $this->abstractMock('\PHPixie\ORM\Data\Type\Document\Node\Document');
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
    
    abstract protected function getPreloader();
}