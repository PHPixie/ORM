<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type\Many;

class Handler extends \PHPixie\ORM\Relationships\Type\Embeds\Handler
{
    protected function mapConditionBuilder($builder, $side, $collection, $plan)
    {
        $config = $side->config();
        $builder->startConditionGroup($collection->logic(), $collection->isNegated());
            
        $builder->andNot($config->path, null);
        $container = $builder->addSubarrayItemPlaceholder($config->path);

        $this->mappers->conditions()->map(
            $container,
            $config->itemModel,
            $collection->conditions(),
            $plan
        );
        
        $builder->endGroup();
    }
    
    public function offsetSet($entity, $config, $offset, $item)
    {
        $this->assertModelName($item, $config->itemModel);
        $this->removeItemFromOwner($item);
        $this->setItem($entity, $config, $offset, $item);
    }

    public function offsetUnset($entity, $config, $offset){
        $this->unsetItems($entity, $config, array($offset));
    }

    public function offsetCreate($entity, $config, $offset, $data){
        $embeddedModel = $this->models->embedded();
        $item = $embeddedModel->loadEntityFromData($config->itemModel, $data);
        $this->setItem($entity, $config, $offset, $item);
        return $item;
    }

    public function removeItems($entity, $config, $items) {
        if(!is_array($items)) {
            $items = array($items);
        }
        $property = $entity->getRelationshipProperty($config->ownerItemsProperty);
        $arrayNodeLoader = $property->value();
        $cachedItems = $arrayNodeLoader->getCachedEntities();

        $offsets = array();
        foreach($items as $item) {
            $this->assertModelName($item, $config->itemModel);
            $offset = array_search($item, $cachedItems, true);
            if($offset === false)
                throw new \PHPixie\ORM\Exception\Relationship("Item specified for removal was not found on the model");
            $offsets[]=$offset;
        }

        $this->unsetItems($entity, $config, $offsets);
    }


    protected function setItem($entity, $config, $offset, $item)
    {
        $property = $entity->getRelationshipProperty($config->ownerItemsProperty);
        $arrayNodeLoader = $property->value();
        
        $count = $arrayNodeLoader->count();

        if($offset === null) {
            $offset = $count;
        }elseif($offset > $count) {
            throw new \PHPixie\ORM\Exception\Relationship("There may be no gaps in items array. Key $offset is larger than item count $count");
        }

        if($offset < $count) {
            $this->unsetCachedItemOwner($arrayNodeLoader, $offset);
        }
        
        $arrayNodeLoader->cacheEntity($offset, $item);

        $document  = $item->data()->document();
        
        $arrayNode = $arrayNodeLoader->arrayNode();
        $arrayNode->offsetSet($offset, $document);
        
        $item->setOwnerRelationship($entity, $config->ownerItemsProperty);
        $this->checkArrayNode($entity, $config, $arrayNode);
        
    }

    protected function unsetItems($entity, $config, $offsets)
    {
        $property = $entity->getRelationshipProperty($config->ownerItemsProperty);
        $arrayNodeLoader = $property->value();
        $cachedEntities = $arrayNodeLoader->getCachedEntities();
        $arrayNode = $arrayNodeLoader->arrayNode();

        sort($offsets, SORT_NUMERIC);

        foreach($offsets as $key => $offset) {

            $cachedEntities[$offset]->unsetOwnerRelationship();

            $adjustedOffset = $offset - $key;
            $arrayNode->offsetUnset($adjustedOffset);
            $arrayNodeLoader->shiftCachedEntities($adjustedOffset);
        }
        
        $this->checkArrayNode($entity, $config, $arrayNode);
    }

    public function removeAllItems($entity, $config) {
        $property = $entity->getRelationshipProperty($config->ownerItemsProperty);
        $arrayNodeLoader = $property->value();
        $cachedEntities = $arrayNodeLoader->getCachedEntities();

        foreach($cachedEntities as $item) {
            $item->unsetOwnerRelationship();
        }

        $arrayNodeLoader->clearCachedEntities();
        $arrayNode = $arrayNodeLoader->arrayNode();
        $arrayNode->clear();
        $this->checkArrayNode($entity, $config, $arrayNode);
    }

    public function loadProperty($config, $entity)
    {
        $arrayNode = $this->checkArrayNode($entity, $config);
        
        $arrayNodeLoader = $this->loaders->arrayNode(
            $config->itemModel,
            $arrayNode,
            $entity,
            $config->ownerItemsProperty
        );
        
        $property = $entity->getRelationshipProperty($config->ownerItemsProperty);
        $property->setValue($arrayNodeLoader);
    }
    
    protected function unsetCachedItemOwner($arrayNodeLoader, $offset)
    {
        $oldItem = $arrayNodeLoader->getCachedEntity($offset);
        if($oldItem !== null) {
            $oldItem->unsetOwnerRelationship();
        }
    }
    
    protected function checkArrayNode($entity, $config, $arrayNode = null)
    {
        $document = $entity->data()->document();
        $documentPlanner = $this->planners->document();
        list($parent, $key) = $documentPlanner->getParentDocumentAndKey($document, $config->path, true);
        
        if($arrayNode === null) {
            $arrayNode = $documentPlanner->getArrayNode($parent, $key, true);
        }
        
        if($arrayNode->count() === 0) {
            $parent->remove($key);
            
        }else{
            $parent->set($key, $arrayNode);
        }
        
        return $arrayNode;
    }
}
