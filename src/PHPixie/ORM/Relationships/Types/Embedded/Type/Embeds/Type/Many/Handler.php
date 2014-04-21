<?php

namespace PHPixie\ORM\Relationships\Types\Embedded\Type\Embedsded\Type\Embeds\Type\Many;

class Handler extends \PHPixie\ORM\Relationships\Types\Embedded\Type\Embedsded\Type\Embeds\Handler
{
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

    public function unset($config, $owner, $key)
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
