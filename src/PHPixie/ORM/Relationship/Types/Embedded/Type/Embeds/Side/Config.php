<?php

namespace PHPixie\ORM\Relationships\Types\Embeds\Side;

class Config extends PHPixie\ORM\Relationship\Side\Config
{
    public $ownerModel;
    public $itemModel;
	public $path;
    public $ownerProperty;
    public $itemProperty;
    
    protected function processConfig($config, $inflector)
    {
        $itemOptionName = $this->itemOptionName();
        $this->ownerModel = $config->get('owner');
        $this->itemModel = $config->get($itemOptionName);
        
        $itemOptionsPrefix = $itemOptionsName.'Options';
        $this->itemProperty = $config->get($itemOptionsPrefix.'.ownerProperty', $this->ownerModel);
        
        $this->ownerProperty = $config->get('ownerOptions.'.$itemOptionName.'Property', null);
        if ($this->ownerProperty === null)
            $this->ownerProperty = $this->defaultOwnerProperty($inflector);
		
		$this->path = $config->get('path', $this->ownerProperty.'_data');
    }
    
    abstract protected function itemOptionName();
    abstract protected function defaultOwnerProperty($inflector);
}
