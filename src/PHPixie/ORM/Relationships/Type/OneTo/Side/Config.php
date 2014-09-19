<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Side;

abstract class Config extends \PHPixie\ORM\Relationships\Relationship\Side\Config
{
    public $ownerModel;
    public $itemModel;
    public $ownerKey;
    public $itemOwnerProperty;
    public $onDelete;

    protected function processConfig($config, $inflector)
    {
        $itemOptionName = $this->itemOptionName();
        $itemPropertyName = $this->ownerPropertyName();
        
        $this->ownerModel = $config->get('owner');
        $this->itemModel = $config->get($itemOptionName);

        $itemOptionsPrefix = $itemOptionName.'Options';
        $this->itemOwnerProperty = $config->get($itemOptionsPrefix.'.ownerProperty', $this->ownerModel);
        $this->ownerKey = $config->get($itemOptionsPrefix.'.ownerKey', $this->itemOwnerProperty.'_id');
        $this->onDelete = $config->get($itemOptionsPrefix.'.onOwnerDelete', 'update');

        $itemProperty = $config->get('ownerOptions.'.$itemOptionName.'Property', null);
        if ($itemProperty === null)
            $itemProperty = $this->defaultOwnerProperty($inflector);
        
        $this->$itemPropertyName = $itemProperty;
    }
    
    public function ownerProperty()
    {
        $property = $this->ownerPropertyName();
        return $this->$property;
    }
    
    abstract protected function itemOptionName();
    abstract protected function ownerPropertyName();
    abstract protected function defaultOwnerProperty($inflector);
}
