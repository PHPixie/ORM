<?php

namespace PHPixie\ORM\Relationships\Types\OneTo\Side;

abstract class Config extends \PHPixie\ORM\Relationship\Side\Config
{
    public $ownerModel;
    public $itemModel;
    public $itemKey;
    public $ownerProperty;
    public $itemProperty;
    
    protected function processConfig($config, $inflector)
    {
        $itemOptionName = $this->itemOptionName();
        $this->ownerModel = $config->get('owner');
        $this->itemModel = $config->get($itemOptionName);
        
        $itemOptionsPrefix = $itemOptionsName.'Options';
        $this->itemProperty = $config->get($itemOptionsPrefix.'.ownerProperty', $this->ownerModel);
        $this->itemKey = $config->get($itemOptionsPrefix.'.ownerKey', $this->itemProperty.'_id');
        
        $this->ownerProperty = $config->get('ownerOptions.itemsProperty', null);
        if ($this->ownerProperty === null)
            $this->ownerProperty = $this->defaultOwnerProperty($inflector);
    }
    
    abstract protected function itemOptionName();
    abstract protected function defaultOwnerProperty($inflector);
}
