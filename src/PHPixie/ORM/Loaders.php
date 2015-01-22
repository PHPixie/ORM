<?php

namespace PHPixie\ORM;

class Loaders
{
    protected $ormBuilder;
    
    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }
    
    public function iterator($loader)
    {
        return new Loaders\Iterator($loader);
    }

    public function multiplePreloader($resultPreloader, $ids)
    {
        return new Loaders\Loader\MultiplePreloader($this, $resultPreloader, $ids);
    }
    
    public function editableProxy($loader)
    {
        return new Loaders\Loader\Proxy\Editable($this, $loader);
    }

    public function cachingProxy($loader)
    {
        return new Loaders\Loader\Proxy\Caching($this, $loader);
    }
    
    public function preloadingProxy($loader)
    {
        return new Loaders\Loader\Proxy\Preloading($this, $loader);
    }
    
    public function reusableResult($repository, $reusableResultStep)
    {
        return new Loaders\Loader\Repository\ReusableResult(
            $this,
            $repository,
            $reusableResultStep
        );
    }
    
    public function dataIterator($repository, $reusableResultStep)
    {
        return new Loaders\Loader\Repository\DataIterator(
            $this,
            $repository,
            $reusableResultStep
        );
    }
    
    public function arrayNode($modelName, $arrayNode, $owner, $ownerPropertyName)
    {
        $embeddedModel = $this->ormBuilder->models()->embedded();
        
        return new Loaders\Loader\Embedded\ArrayNode(
            $this,
            $embeddedModel,
            $modelName,
            $arrayNode,
            $owner,
            $ownerPropertyName
        );
    }

}