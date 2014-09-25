<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many;

class Handler extends \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Handler
{
    public function offsetSet($model, $config, $key, $item)
    {
        $property = $model->relationshipProperty($config->ownerItemsProperty);
        $arrayNodeLoader = $property->value();
        $arrayNodeLoader->cacheModel($key, $item);

        $arrayNode = $this->getArrayNode($model, $config->path);
        $document  = $item->data()->document();
        $arrayNode->offsetSet($key, $item);

        $item->setOwner($model);


    }
    public function offsetUnset(){}
    public function offsetCreate(){}
    public function removeItems(){}
    public function removeAllItems(){}
    public function loadProperty($config, $model)
    {
        $arrayNode = $this->getArray($model, $config->path, true);
        $loader = $this->loaders->embeddedArrayAcces($model, $arrayNode);
        return $this->loaders->cachingProxy($loader);
    }

    public function add($config, $owner, $key = null)
    {
        $array = $this->getArray($owner, $config->path, true);
        $document = $this->planners->document()->arrayAddDocument($array, $key);

        return $this->embeddedModel($config, $document);
    }

    public function get($config, $owner, $key)
    {
        $array = $this->getArray($owner, $config->path);
        if ($array === null)
            return null;

        $document = $this->planners->document()->arrayGetDocument($array, $key);

        return $this->embeddedModel($config, $document, $owner);
    }

    public function exists($config, $owner, $key)
    {
        $array = $this->getArray($owner, $config->path);
        if ($array === null)
            return false;

        return $this->planners->document()->arrayExists($array, $key);
    }

    public function set($config, $owner, $item, $key)
    {
        $this->assertModelName($item, $config->itemModel);
        $array = $this->getArray($owner, $config->path, true);
        $this->planners->document()->arraySet($array, $key, $item->data()->document());
    }

    public function unsetOffset($config, $owner, $key)
    {
        $documentPlanner = $this->planners->document();
        $array = $this->getArray($owner, $config->path, true);
        if($array === null && $documentPlanner->arrayExists($array, $key))
            $documentPlanner->arrayUnset($array, $key);
    }

    public function count($config, $owner)
    {
        $array = $this->getArray($owner, $config->path);
        if ($array === null)
            return 0;

        return $this->planners->document()->arrayCount($array);
    }

    public function clear($config, $owner)
    {
        $array = $this->getArray($owner, $config->path);
        if ($array !== null)
            $this->planners->document()->arrayClear($array);
    }

    protected function getArray($model, $path, $createMissing = false)
    {
        $documentPlanner = $this->planners->document();
        list($parent, $key) = $this->getParentAndKey($model, $path, $createMissing);
        if ($parent === null)
            return null;

        $array = $documentPlanner->getArray($parent, $key);
        if ($array === null) {
            if(!$createMissing)

                return null;
            $array = $documentPlanner->addArray($parent, $key);
        }

        return $array;
    }

    public function propertyLoader($property)
    {
        return $this->loaders->arrayAccess($property);
    }

    protected function fieldPrefix($oldPrefix, $path)
    {
        return parent::fieldPrefix($oldPrefix, $path).'.$';
    }
}
