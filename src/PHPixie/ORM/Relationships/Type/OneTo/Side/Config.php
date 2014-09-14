<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Side;

abstract class Config extends \PHPixie\ORM\Relationships\Relationship\Side\Config
{
    public $ownerModel;
    public $itemModel;
    public $ownerKey;
    public $ownerProperty;
    public $itemProperty;
    public $onDelete;

    protected function processConfig($config, $inflector)
    {
        $itemOptionName = $this->itemOptionName();
        $this->ownerModel = $config->get('owner');
        $this->itemModel = $config->get($itemOptionName);

        $itemOptionsPrefix = $itemOptionName.'Options';
        $this->itemProperty = $config->get($itemOptionsPrefix.'.property', $this->ownerModel);
        $this->ownerKey = $config->get($itemOptionsPrefix.'.ownerKey', $this->itemProperty.'_id');
        $this->onDelete = $config->get($itemOptionsPrefix.'.onOwnerDelete', 'update');

        $this->ownerProperty = $config->get('ownerOptions.property', null);
        if ($this->ownerProperty === null)
            $this->ownerProperty = $this->defaultOwnerProperty($inflector);
    }

    abstract protected function itemOptionName();
    abstract protected function defaultOwnerProperty($inflector);
}
