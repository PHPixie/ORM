<?php

namespace PHPixie\ORM;

class Loaders
{
    public function iterator($loader)
    {
        return new Loaders\Iterator($loader);
    }

    public function editable($loader)
    {
        return new Loaders\Loader\Editable($this, $loader);
    }

    public function caching($loader)
    {
        return new Loaders\Loader\Editable($this, $loader);
    }
    
    public function arrayAccess($arrayAccess)
    {
        return new Loaders\Loader\Editable($this, $arrayAccess);
    }

    public function resultPreloader($resultPreloader, $ids)
    {
        return new Loaders\Loader\Preloader($this, $resultPreloader, $ids);
    }

    public function reusableStep($repository, $reusableResultStep)
    {
        return new Loaders\Loader\Preloadable\Repository\ReusableStep($this, $repository, $reusableResultStep);
    }

    public function iterator($repository, $iterator)
    {
        return new Loaders\Loader\Result\SingleUse($this, $repository, $iterator);
    }
}
