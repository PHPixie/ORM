<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Side;

abstract class Config extends \PHPixie\ORM\Relationships\Relationship\Implementation\Side\Config
{
    public $ownerModel;
    public $itemModel;
    public $ownerKey;
    public $itemOwnerProperty;
    public $onDelete;

    protected function processConfig($configSlice, $inflector)
    {
        $itemOptionName = $this->itemOptionName();
        $itemPropertyName = $this->ownerPropertyName();
        
        $this->ownerModel = $configSlice->getRequired('owner');
        $this->itemModel = $configSlice->getRequired($itemOptionName);

        $itemOptionsPrefix = $itemOptionName.'Options';
        $this->itemOwnerProperty = $configSlice->get($itemOptionsPrefix.'.ownerProperty', $this->ownerModel);
        $this->ownerKey = $configSlice->get($itemOptionsPrefix.'.ownerKey', $this->itemOwnerProperty.'Id');
        $this->onDelete = $configSlice->get($itemOptionsPrefix.'.onOwnerDelete', 'update');

        $itemProperty = $configSlice->get('ownerOptions.'.$itemOptionName.'Property', null);
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
