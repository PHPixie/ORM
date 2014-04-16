<?php

namespace PHPixie\ORM\Relationships\OneTo\Type\Many\Loader;

class Owner extends \PHPixie\ORM\Loaders\Loader {
    
    protected $loader;
    protected $itemPropertyName;
    protected $owner;
    protected $processedItems = array();
    
    public function __construct($loaders, $loader, $itemPropertyName, $owner)
    {
        parent::__construct($loaders);
        $this->loader = $loader;
        $this->ownerPropertyName = $ownerPropertyName;
        $this->owner = $owner;
    }
    
    public function offsetExists($offset)
    {
        return $this->loader->offsetExists($offset);
    }
    
    public function getByOffset($offset)
    {
        $item = $this->loader->getByOffset($offset);
        if(!array_key_exists($id, $this->processedItems)) {
            $itemPropertyName = $this->itemPropertyName;
            $item->$itemPropertyName->setValue($this->owner);
            $this->processedItems[$item->id()] = true;
        }
    }
}