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

        $this->groupMapper->mapConditionGroup($config->itemModel, $builder, $group, $plan, $pathPrefix);
    }

    public function loadProperty($config, $model)
    {
        $document = $this->getDocument($model, $config->path);
        $model = $this->repository->loadFromDocument($data);
        $model->setOwnerRelationship($this->owner, $this->ownerPropertyName);
        return $model;
    }

    public function setItem($model, $config, $item)
    {
        $property = $model->relationshipProperty($config->ownerItemsProperty);
        $this->unsetCurrentItemOwner($property);

        list($document, $key) = $this->getParentDocumentAndKey($model, $config->path);
        $document->set($key, $item->data()->document());
        $property->setValue($item);
    }

    public function removeItem()
    {
        $property = $model->relationshipProperty($config->ownerItemsProperty);
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

    protected function unsetCurrentItemOwner($peoperty)
    {
        $oldItem = $property->value();
        if($oldItem !== null) {
            $oldItem->unsetOwnerRelationship();
        }
    }

}
