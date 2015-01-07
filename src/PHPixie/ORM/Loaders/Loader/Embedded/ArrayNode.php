<?php

namespace PHPixie\ORM\Loaders\Loader\Embedded;

class ArrayNode extends \PHPixie\ORM\Loaders\Loader\Embedded
{
    protected $arrayNode;
    protected $owner;
    protected $ownerPropertyName;
    protected $cachedEntities = array();


    public function __construct($loaders, $repository, $arrayNode, $owner, $ownerPropertyName)
    {
        parent::__construct($loaders, $repository);
        $this->arrayNode = $arrayNode;
        $this->owner = $owner;
        $this->ownerPropertyName = $ownerPropertyName;
    }

    public function offsetExists($offset)
    {
        return $this->arrayNode->offsetExists($offset);
    }

    public function getByOffset($offset)
    {
        if(!array_key_exists($offset, $this->cachedEntities)) {

            if(!$this->offsetExists($offset))
                throw new \PHPixie\ORM\Exception\Loader("Offset $offset does not exist.");

            $document = $this->arrayNode->offsetGet($offset);
            $this->cachedEntities[$offset] = $this->loadEntity($document);
        }

        return $this->cachedEntities[$offset];
    }

    public function cacheEntity($offset, $model)
    {
        $this->cachedEntities[$offset] = $model;
    }

    public function shiftCachedEntities($offset)
    {
        if(array_key_exists($offset, $this->cachedEntities))
            unset($this->cachedEntities[$offset]);

        $keys = array_keys($this->cachedEntities);
        foreach($keys as $key) {
            if($key <= $offset)
                continue;

            $this->cachedEntities[$key-1] = $this->cachedEntities[$key];
            unset($this->cachedEntities[$key]);
        }
    }

    public function getCachedEntity($offset)
    {
        if(array_key_exists($offset, $this->cachedEntities))
            return $this->cachedEntities[$offset];

        return null;
    }

    public function clearCachedEntities()
    {
        $this->cachedEntities = array();
    }

    public function count()
    {
        return $this->arrayNode->count();
    }

    public function arrayNode()
    {
        return $this->arrayNode;
    }

    public function owner()
    {
        return $this->owner;
    }

    public function ownerPropertyName()
    {
        return $this->ownerPropertyName;
    }

    public function cachedEntities()
    {
        return $this->cachedEntities;
    }
}
