<?php

namespace PHPixie\ORM\Model\Preloader;

abstract class Single extends \PHPixie\ORM\Model\Preloader
{
    protected $map;

    public function loadFor($owner)
    {
        if ($this->map === null)
            $this->map = $this->mapItems();
        
        return $this->getModel($owner->id());
    }
    
    protected abstract function mapItems();

}
