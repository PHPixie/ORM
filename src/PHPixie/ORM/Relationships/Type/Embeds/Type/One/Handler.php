<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type\One;

class Handler extends \PHPixie\ORM\Relationships\Type\Embeds\Handler
{
    protected function mapConditionBuilder($builder, $side, $collection, $plan)
    {
        $config = $side->config();
        $builder->startConditionGroup($collection->logic(), $collection->isNegated());
            
        $builder->andNot($config->path, null);
        $container = $builder->addSubdocumentPlaceholder($config->path);

        $this->mappers->conditions()->map(
            $container,
            $config->itemModel,
            $collection->conditions(),
            $plan
        );
        
        $builder->endGroup();
    }


    public function loadProperty($config, $owner)
    {
        $document = $this->getEntityDocument($owner);
        $item = $this->planners->document()->getDocument($document, $config->path, false);
        
        if($item !== null) {
            $item = $this->models->embedded()->loadEntity($config->itemModel, $item);
            $item->setOwnerRelationship($owner, $config->ownerItemProperty);
        }
        $property = $owner->getRelationshipProperty($config->ownerItemProperty);
        $property->setValue($item);
    }

    public function setItem($entity, $config, $item)
    {
        $this->assertModelName($item, $config->itemModel);
        $this->removeItemFromOwner($item);
        $this->setItemModel($entity, $config, $item);
    }

    public function removeItem($entity, $config)
    {
        $property = $entity->getRelationshipProperty($config->ownerItemProperty);
        $this->unsetCurrentItemOwner($property);
        
        list($document, $key) = $this->getParentDocumentAndKey($entity, $config);
        $document->remove($key);
        $property->setValue(null);
    }

    public function createItem($entity, $config, $data)
    {
        $item = $this->models->embedded()->loadEntityFromData($config->itemModel, $data);
        $this->setItemModel($entity, $config, $item);
    }

    protected function setItemModel($entity, $config, $item)
    {
        $property = $entity->getRelationshipProperty($config->ownerItemProperty);
        $this->unsetCurrentItemOwner($property);
        
        list($document, $key) = $this->getParentDocumentAndKey($entity, $config);
        $document->set($key, $item->data()->document());
        $item->setOwnerRelationship($entity, $config->ownerItemProperty);
        $property->setValue($item);
    }

    protected function unsetCurrentItemOwner($property)
    {
        if(!$property->isLoaded())
            return;

        $oldItem = $property->value();
        if($oldItem !== null) {
            $oldItem->unsetOwnerRelationship();
        }
    }
    
    protected function getEntityDocument($entity)
    {
        return $entity->data()->document();
    }
    
    protected function getParentDocumentAndKey($entity, $config)
    {
        $document = $this->getEntityDocument($entity);
        $documentPlanner = $this->planners->document();
        return $documentPlanner->getParentDocumentAndKey($document, $config->path, true);
    }

}
