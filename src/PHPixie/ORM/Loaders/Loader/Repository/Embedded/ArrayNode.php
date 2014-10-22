<?php

namespace PHPixie\ORM\Loaders\Loader\Repository\Embedded;

class ArrayNode extends \PHPixie\ORM\Loaders\Loader\Repository\Embedded
{
    protected $arrayNode;
    protected $owner;
    protected $ownerPropertyName;
    protected $cachedModels = array();


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
        if(!array_key_exists($offset, $this->cachedModels)) {
            
            if(!$this->offsetExists($offset))
                throw new \PHPixie\ORM\Exception\Loader("Offset $offset does not exist.");

            $document = $this->arrayNode->offsetGet($offset);
            $this->cachedModels[$offset] = $this->loadModel($document);
        }

        return $this->cachedModels[$offset];
    }

    public function cacheModel($offset, $model)
    {
        $this->models[$offset] = $model;
    }

    public function shiftCachedModels($offset, $length = 1, $replacement = array())
    {

    }

    public function getCachedModel($offset)
    {
            if(array_key_exists($key, $this->models))
                return $this->models[$offset];

            return null;
    }

    public function clearCachedModels()
    {
        $this->models = array();
    }

    public function count()
    {
        return $this->arrayNode->count();
    }

    public function arrayNode()
    {
        return $this->arrayNode;
    }

    public function cachedModels()
    {
        return $this->models;
    }
}
