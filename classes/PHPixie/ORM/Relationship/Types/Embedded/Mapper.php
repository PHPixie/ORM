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
        
        //check that items is inside owner
        
        return array(
            'owner_repo' => $ownerRepo,
            'item_repo' => $itemRepo,

            'owner_property' => $this->inflector->plural($itemsModel),
            'item_property' => $ownerModel
        );
    }

}
