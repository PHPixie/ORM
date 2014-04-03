<?php

namespace PHPixie\ORM\Model\Preloader;

abstract class Multiple extends \PHPixie\ORM\Model\Preloader
{
    protected $map;

    public function loadFor($owner)
    {
        if ($this->map === null)
            $this->map = $this->mapItems();
        
        $ids = $this->itemIdsFor($owner);
        return $this->loader($ids);
    }
    
    protected function loader($ids)
    {
        return $this->loaders->preloader($this, $ids);
    }
    
    protected abstract function mapItems();
    protected abstract function itemIdsFor();
}
