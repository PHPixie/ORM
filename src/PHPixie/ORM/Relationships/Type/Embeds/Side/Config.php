<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Side;

abstract class Config extends \PHPixie\ORM\Relationships\Relationship\Implementation\Side\Config
{
    public $ownerModel;
    public $itemModel;
    public $path;

    protected function processConfig($config, $inflector)
    {
        $itemOptionName = $this->itemOptionName();
        $itemPropertyName = $this->ownerPropertyName();
        
        $this->ownerModel = $config->getRequired('owner');
        $this->itemModel = $config->getRequired($itemOptionName);
        
        $itemProperty = $config->get('ownerOptions.'.$itemOptionName.'Property', null);
        if ($itemProperty === null)
            $itemProperty = $this->defaultOwnerProperty($inflector);
        $this->$itemPropertyName = $itemProperty;
        

        $itemOptionsPrefix = $itemOptionName.'Options';
        $this->path = $config->get($itemOptionsPrefix.'.path', $itemProperty);
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
