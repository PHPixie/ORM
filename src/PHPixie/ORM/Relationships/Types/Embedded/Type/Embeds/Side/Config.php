<?php

namespace PHPixie\ORM\Relationships\Types\Embedded\Type\Embeds\Side;

class Config extends PHPixie\ORM\Relationships\Relationship\Side\Config
{
    public $ownerModel;
    public $ownerProperty;
    public $itemModel;
    public $path;

    protected function processConfig($config, $inflector)
    {
        $itemOptionName = $this->itemOptionName();
        $this->ownerModel = $config->get('owner');
        $this->itemModel = $config->get($itemOptionName);

        $itemOptionsPrefix = $itemOptionsName.'Options';

        $this->ownerProperty = $config->get('ownerOptions.'.$itemOptionName.'Property', null);
        if ($this->ownerProperty === null)
            $this->ownerProperty = $this->defaultOwnerProperty($inflector);

        $this->path = $config->get('path', $this->ownerProperty.'_data');
    }

    abstract protected function itemOptionName();
    abstract protected function defaultOwnerProperty($inflector);
}
