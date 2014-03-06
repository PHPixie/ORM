<?php

namespace PHPixie\ORM\Model;

abstract class Preloader
{
    protected $link;
    protected $loader;
    protected $reusableResultStep;
    protected $items;

    public function __construct($link, $loader, $reusableResultStep)
    {
        $this->link = $link;
        $this->loader = $loader;
        $this->reusableResultStep = $reusableResultStep;
    }

    public function propertyName()
    {
        return $this->propertyName;
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

    abstract public function loadFor($owner);

}
