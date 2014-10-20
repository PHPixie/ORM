<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many;

class Handler extends \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Handler
{
    public function mapRelationshipBuilder($side, $builder, $group, $plan, $pathPrefix = null)
    {
        $config = $side->config();
        $subdocument = $this->ormBuilder->subdocumentCondition();
        $this->embeddedGroupMapper->mapConditions($config->itemModel, $subdocument, $group->conditions(), $plan);
        $builder->addOperatorCondition($group->logic(), $group->negated(), $config->path, 'elemMatch', $subdocument);
    }

    public function offsetSet($model, $config, $key, $item)
    {
        $this->assertModelName($item, $config->itemModel);
        $this->removeItemFromOwner($item);
        $this->setItem($model, $config, $key, $item);
    }

    public function offsetUnset($model, $config, $key){
        $this->unsetItems($model, $config, array($key));
    }

    public function offsetCreate($model, $config, $key, $data){
        $repository = $this->repositories->get($config->itemModel);
        $item = $repository->load($data);
        $this->setItem($model, $config, $key, $item);
    }

    public function removeItems($model, $config, $items) {
        if(!is_array($items)) {
            $items = array($items);
        }
        $property = $model->relationshipProperty($config->ownerItemsProperty);
        $arrayNodeLoader = $property->value();
        $cachedItems = $arrayNodeLoader->cachedModels();

        $offsets = array();
        foreach($items as $item) {
            $this->assertModelName($item, $config->itemModel);
            $offset = array_search($item, $cachedItems, true);
            if($offset === false)
                throw new \PHPixie\ORM\Exception\Relationship("Item specified for removal was not found on the model");
            $offsets[]=$offset;
        }

        $this->unsetItems($model, $config, $offsets);
    }


    protected function setItem($model, $config, $key, $item)
    {
        $arrayNode = $this->getArrayNode($model, $config->path);
        $count = $arrayNode->count();

        if($key === null) {
            $key = $count;
        }elseif($key > $count) {
            throw new \PHPixie\ORM\Exception\Relationship("There may be no gaps in items array. Key $key is larger than item count $count");
        }

        $property = $model->relationshipProperty($config->ownerItemsProperty);
        $arrayNodeLoader = $property->value();
        $arrayNodeLoader->cacheModel($key, $item);

        $document  = $item->data()->document();
        $arrayNode->offsetSet($key, $document);

        $item->setOwnerRelationship($model, $config->ownerItemsProperty);
    }

    protected function unsetItems($model, $config, $offsets)
    {
        $property = $model->relationshipProperty($config->ownerItemsProperty);
        $arrayNodeLoader = $property->value();
        $cachedModels = $arrayNodeLoader->cachedModels();
        $arrayNode = $this->getArrayNode($model, $config->path);

        sort($offsets, SORT_NUMERIC);

        foreach($offsets as $key => $offset) {

            $cachedModels[$offset]->unsetOwnerRelationship();

            $adjustedOffset = $offset - $key;
            $arrayNode->offsetUnset($adjustedOffset);
            $arrayNodeLoader->shiftCachedModels($adjustedOffset);
        }
    }

    public function removeAllItems($model, $config) {
        $property = $model->relationshipProperty($config->ownerItemsProperty);
        $arrayNodeLoader = $property->value();
        $cachedModels = $arrayNodeLoader->cachedModels();

        foreach($cachedModels as $item) {
            $item->unsetOwnerRelationship();
        }

        $arrayNodeLoader->clearCachedModels();
        $arrayNode = $this->getArrayNode($model, $config->path);
        $arrayNode->clear();
    }

    public function loadProperty($config, $model)
    {
        $itemRepository = $this->repositories->get($config->itemModel);
        $arrayNode = $this->getArrayNode($model, $config->path);
        return $this->loaders->arrayNode($itemRepository, $model, $arrayNode);
    }

}
