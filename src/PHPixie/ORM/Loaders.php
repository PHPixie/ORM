<?php

namespace PHPixie\ORM;

class Loaders
{
    public function iterator($loader)
    {
        return new Loaders\Iterator($loader);
    }

    public function arrayAccess($arrayAccess)
    {
        return new Loaders\Loader\ArrayAccess($this, $arrayAccess);
    }
    
    public function resultPreloader($resultPreloader, $ids)
    {
        return new Loaders\Loader\ResultPreloader($this, $resultPreloader, $ids);
    }
    
    public function editable($loader)
    {
        return new Loaders\Loader\Proxy\Editable($this, $loader);
    }

    public function caching($loader)
    {
        return new Loaders\Loader\Proxy\Caching($this, $loader);
    }
    
    public function preloading($loader)
    {
        return new Loaders\Loader\Proxy\Preloading($this, $loader);
    }
    
    public function reusableResult($repository, $reusableResultStep)
    {
        return new Loaders\Loader\Repository\ReusableResult($this, $repository, $reusableResultStep);
    }
    
    public function dataIterator($repository, $reusableResultStep)
    {
        return new Loaders\Loader\Repository\DataIterator($this, $repository, $reusableResultStep);
    }

}