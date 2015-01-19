<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type\One;

class Handler extends \PHPixie\ORM\Relationships\Type\Embeds\Handler
{
    protected function mapConditionBuilder($builder, $side, $collection, $plan)
    {
        $path = $side->config()->path;
        $builder->startConditionGroup($collection->logic(), $collection->isNegated());
            
        $builder->whereNot($path, null);
        $container = $builder->addSubdocumentPlaceholder(
            $config->path
        );

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
        $document = $this->getDocument($owner, $config->path);
        $item = $this->models->embedded()->loadEntity($config->itemModel, $document);
        $item->setOwnerRelationship($owner, $config->ownerItemProperty);
        return $item;
    }

    public function setItem($model, $config, $item)
    {
        $this->assertModelName($item, $config->itemModel);
        $this->removeItemFromOwner($item);
        $this->setItemModel($model, $config, $item);
    }

    public function removeItem($model, $config)
    {
        $property = $model->getRelationshipProperty($config->ownerItemProperty);
        $this->unsetCurrentItemOwner($property);
        list($document, $key) = $this->getParentDocumentAndKey($model, $config->path);
        $document->remove($key);
        $property->setValue(null);
    }

    public function createItem($model, $config, $data)
    {
        $item = $this->models->embedded()->loadEntityFromData($config->itemModel, $data);
        $this->setItemModel($model, $config, $item);
    }

    protected function setItemModel($model, $config, $item)
    {
        $property = $model->getRelationshipProperty($config->ownerItemProperty);
        $this->unsetCurrentItemOwner($property);

        list($document, $key) = $this->getParentDocumentAndKey($model, $config->path);
        $document->set($key, $item->data()->document());
        $item->setOwnerRelationship($model, $config->ownerItemProperty);
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

}
