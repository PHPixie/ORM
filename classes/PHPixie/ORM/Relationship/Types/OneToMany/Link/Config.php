<?php

namespace PHPixie\ORM\Relationships\Types\OneToMany\Link;

class Config extends PHPixie\ORM\Relationship\Side\Config
{
    public $ownerModel;
    public $itemModel;
    public $itemKey;
    public $ownerProperty;
    public $itemProperty;

    protected function processConfig($config, $inflector)
    {
        $this->ownerModel = $config->get('owner.model');
        $this->itemModel = $config->get('items.model');
        $this->itemKey = $config->get('items.owner_key', $this->ownerModel.'_id');

        if (($this->ownerProperty = $config->get('owner.items_property', null)) === null)
            $this->ownerProperty = $inflector->plural($itemsModel);

        $this->itemProperty = $config->get('owner.owner_property', $ownerModel);
    }
}
