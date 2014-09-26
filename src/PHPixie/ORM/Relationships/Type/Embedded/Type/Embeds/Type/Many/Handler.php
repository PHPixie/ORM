<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many;

class Handler extends \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Handler
{
    public function offsetSet($model, $config, $key, $item)
    {
        $this->assertModelName($model, $config->itemModel);
        $this->removeOwner($item);
        $this->setItem($model, $config, $key, $item);
    }

    public function offsetUnset($model, $config, $key){
        $this->assertModelName($model, $config->itemModel);
        $this->unsetItems($model, $config, array($key));
    }

    public function offsetCreate($model, $config, $key, $data){
        $repository = $this->repositories->get($config->itemModel);
        $item = $repository->create($data);
        $this->setItem($model, $config, $key, $item);
    }

    public function removeItems($model, $config, $items) {
        $property = $model->relationshipProperty($config->ownerItemsProperty);
        $arrayNodeLoader = $property->value();
        $cachedItems = $this->arrayNodeLoader->getCachedModels();

        $offsets = array();
        foreach($items as $item) {
            $offset = array_search($item, $cachedItems);
            if($offset === false)
                throw new \PHPixie\ORM\Exception\Relationship("Item specified for removal was not found on the model");
            $offsets[]=$offset;
        }

        $this->unsetItems($model, $config, $offsets);
    }


    protected function setItem($model, $config, $key, $item)
    {
        $property = $model->relationshipProperty($config->ownerItemsProperty);
        $arrayNodeLoader = $property->value();
        $arrayNodeLoader->cacheModel($key, $item);

        $arrayNode = $this->getArrayNode($model, $config->path);
        $document  = $item->data()->document();
        $arrayNode->offsetSet($key, $document);

        $item->setOwnerRelationship($model, $config->ownerItemsProperty);
    }

    protected function unsetItems($model, $config, $offsets)
    {
        $property = $model->relationshipProperty($config->ownerItemsProperty);
        $arrayNodeLoader = $property->value();
        $cachedModels = $arrayNodeLoader->getCachedModels();

        $arrayNode = $this->getArrayNode($model, $config->path);

        sort($offsets, SORT_NUMERIC);

        foreach($offsets as $key => $offset) {
            $cachedModels[$offset]->setOwnerRelationship(null);

            $adjustedOffset = $offset - $key;
            $arrayNode->offsetUnset($adjustedOffset);
            $arrayNodeLoader->offsetGet();
            $arrayNodeLoader->shiftCachedModels($adjustedOffset);
        }
    }

    public function removeAllItems($model, $config) {
        $property = $model->relationshipProperty($config->ownerItemsProperty);
        $arrayNodeLoader = $property->value();
        $arrayNodeLoader->clearCachedModels();
        $arrayNode = $this->getArrayNode($model, $config->path);
        $arrayNode->empty();
    }

    public function loadProperty($config, $model)
    {
        $arrayNode = $this->getArray($model, $config->path, true);
        $loader = $this->loaders->embeddedArrayAcces($model, $arrayNode);
        return $this->loaders->cachingProxy($loader);
    }

}
