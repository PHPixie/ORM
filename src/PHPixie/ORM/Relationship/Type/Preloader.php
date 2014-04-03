<?php

namespace PHPixie\ORM\Model;

abstract class Preloader
{
    protected $loaders;
    protected $side;
    protected $loader;
    protected $resultStep;
    protected $items = array();

    public function __construct($loaders, $side, $loader)
    {
        $this->loaders    = $loaders;
        $this->side       = $side;
        $this->loader = $loader;
    }

    public function getModel($id)
    {
        
        $data = $this->items[$id];
        if($data instanceof \PHPixie\ORM\Model)
            return $data;

        $model = $this->loader->load($data);
        $this->items[$id] = $model;

        return $model;
    }
    
}
