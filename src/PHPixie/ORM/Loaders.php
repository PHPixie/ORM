<?php

namespace PHPixie\ORM;

/**
 * Class Loaders
 * @package PHPixie\ORM
 */
class Loaders
{
    /**
     * @type \PHPixie\ORM\Builder
     */
    protected $ormBuilder;

    /**
     * Loaders constructor.
     * @param $ormBuilder \PHPixie\ORM\Builder
     */
    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }

    /**
     * @param $loader
     * @return Loaders\Iterator
     */
    public function iterator($loader)
    {
        return new Loaders\Iterator($loader);
    }

    /**
     * @param $resultPreloader
     * @param $ids
     * @return Loaders\Loader\MultiplePreloader
     */
    public function multiplePreloader($resultPreloader, $ids)
    {
        return new Loaders\Loader\MultiplePreloader($this, $resultPreloader, $ids);
    }

    /**
     * @param $loader
     * @return Loaders\Loader\Proxy\Editable
     */
    public function editableProxy($loader)
    {
        return new Loaders\Loader\Proxy\Editable($this, $loader);
    }

    /**
     * @param $loader
     * @return Loaders\Loader\Proxy\Caching
     */
    public function cachingProxy($loader)
    {
        return new Loaders\Loader\Proxy\Caching($this, $loader);
    }

    /**
     * @param $loader
     * @return Loaders\Loader\Proxy\Preloading
     */
    public function preloadingProxy($loader)
    {
        return new Loaders\Loader\Proxy\Preloading($this, $loader);
    }

    /**
     * @param $repository
     * @param $reusableResultStep
     * @return Loaders\Loader\Repository\ReusableResult
     */
    public function reusableResult($repository, $reusableResultStep)
    {
        return new Loaders\Loader\Repository\ReusableResult(
            $this,
            $repository,
            $reusableResultStep
        );
    }

    /**
     * @param $repository
     * @param $reusableResultStep
     * @return Loaders\Loader\Repository\DataIterator
     */
    public function dataIterator($repository, $reusableResultStep)
    {
        return new Loaders\Loader\Repository\DataIterator(
            $this,
            $repository,
            $reusableResultStep
        );
    }

    /**
     * @param $modelName
     * @param $arrayNode
     * @param $owner
     * @param $ownerPropertyName
     * @return Loaders\Loader\Embedded\ArrayNode
     */
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