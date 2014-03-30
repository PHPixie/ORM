<?php

namespace PHPixie\ORM\Relationships\Types\OneToMany\Side;

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
        $this->itemModel = $config->get('item.model');
        $this->itemKey = $config->get('item.owner_key', $this->ownerModel.'_id');

        $this->ownerProperty = $config->get('owner.item_property', $this->itemModel)
        $this->itemProperty = $config->get('owner.owner_property', $this->ownerModel);
    }
}
