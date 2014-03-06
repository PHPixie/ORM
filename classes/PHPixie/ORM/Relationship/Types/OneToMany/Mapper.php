<?php

namespace PHPixie\ORM\Relationships\OneToMany;

class Mapper
{
    protected function normalizeConfig($config)
    {
        $ownerModel = $config->get('owner.model');
        $itemModel = $config->get('items.model');

        $ownerRepo = $this->registry->get($ownerModel);
        $itemRepo = $this->registry->get($itemModel);

        return array(
            'owner_repo' => $ownerRepo,
            'item_repo' => $itemRepo,

            'item_key' => $config->get('items.owner_id', $ownerModel.'_id'),

            'owner_property' => $this->inflector->plural($itemsModel),
            'item_property' => $ownerModel
        );
    }

}
