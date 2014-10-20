<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One;

class Handler extends \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Handler
{
    public function mapRelationshipBuilder($side, $builder, $group, $plan, $pathPrefix = null)
    {
        $config = $side->config();
        if($pathPrefix === null) {
            $pathPrefix = $config->path;
        }else{
            $pathPrefix = $pathPrefix.'.'.$config->path;
        }
        $this->embeddedGroupMapper->mapConditionGroup($config->itemModel, $builder, $group, $plan, $pathPrefix);
    }

    public function loadProperty($config, $model)
    {
        $repository = $this->repositories->get($config->itemModel);
        $document = $this->getDocument($model, $config->path);
        $item = $repository->loadFromDocument($document);
        $item->setOwnerRelationship($model, $config->ownerItemProperty);
        return $item;
    }

    public function setItem($model, $config, $item)
    {
        $property = $model->relationshipProperty($config->ownerItemProperty);
        $this->unsetCurrentItemOwner($property);

        list($document, $key) = $this->getParentDocumentAndKey($model, $config->path);
        $document->set($key, $item->data()->document());
        $item->setOwnerRelationship($model, $config->ownerItemProperty);
        $property->setValue($item);
    }

    public function removeItem($model, $config)
    {
        $property = $model->relationshipProperty($config->ownerItemProperty);
        $this->unsetCurrentItemOwner($property);
        list($document, $key) = $this->getParentDocumentAndKey($model, $config->path);
        $document->remove($key);
        $property->setValue(null);
    }

    public function createItem($model, $config, $data)
    {
        $repository = $this->repositories->get($config->itemModel);
        $item = $repository->load($data);
        $this->setItem($model, $config, $item);
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
