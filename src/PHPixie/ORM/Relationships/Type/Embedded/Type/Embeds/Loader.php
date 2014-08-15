<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds;

abstract class Loader extends \PHPixie\ORM\Loaders\Loader\Preloadable
{
    protected $config
    protected $ownerLoader;
    protected $items;

    public function __construct($loaders, $config, $ownerLoader)
    {
        parent::__construct($loaders);
        $this->config = $config;
        $this->ownerLoader = $ownerLoader;
    }

    public function offsetExists($offset)
    {
        $this->requireLoadedItems();

        return array_key_exists($offset, $this->items);
    }

    public function getModelByOffset($offset)
    {
        $this->requireLoadedItems();

        return $this->items[$offset];
    }

    public function requireLoadedItems()
    {
        if($this->items === null)
            $this->loadItems();
    }

    protected function loadItems()
    {
        $this->items = array();
        $ownerProperty = $this->config->ownerProperty;
        foreach($this->ownerLoader as $owner)
            $this->addPropertyItems($owner->$ownerProperty);
    }

    abstract protected function addPropertyItems($property);
}
