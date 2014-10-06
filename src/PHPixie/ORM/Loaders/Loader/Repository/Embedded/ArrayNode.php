<?php

namespace PHPixie\ORM\Loaders\Loader\Repository\Embedded;

class ArrayNode extends \PHPixie\ORM\Loaders\Loader\Repository\Embedded
{
    protected $arrayNode;
    protected $models;

    public function __construct($loaders, $repository, $owner, $arrayNode)
    {
        parent::__construct($loaders, $repository, $owner);
        $this->arrayNode = $arrayNode;
    }

    public function offsetExists($offset)
    {
        return $this->arrayNode->offsetExists($offset);
    }

    public function getByOffset($offset)
    {
        $data = $this->arrayNode->offsetGet($offset);
        return $this->loadModel($data);
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
    
    public function cacheModel($offset, $model)
    {
        $this->models[$offset] = $model;
    }

    public function shiftCachedModels($offset, $length = 1, $replacement = array())
    {
        
    }
    
    public function clearCachedModels()
    {
        $this->models = array();
    }
}
