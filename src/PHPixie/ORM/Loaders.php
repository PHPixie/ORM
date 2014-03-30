<?php

namespace PHPixie\ORM;

class Loaders
{
    public function iterator($loader)
    {
        return new Loaders\Iterator($loader);
    }
    
    public function preloader($preloader, $ids)
    {
        return new Loaders\Loader\Preloader($this, $preloader, $ids);
    }
    
    public function reusableResult($repository, $reusableResultStep)
    {
        return new Loaders\Loader\Result\Reusable($this, $repository, $reusableResultStep);
    }
    
    public function singleUseResult($repository, $resultStep)
    {
        return new Loaders\Loader\Result\SingleUse($this, $repository, $resultStep);
    }
}