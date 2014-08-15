<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\One\Preloader;

class Item extends \PHPixie\ORM\Relationships\Relationship\Preloader\Result\Single
{

    protected function mapItems()
    {
        $idField = $this->loader->repository()->idField();
        $key = $this->side->config()->itemKey;

        $fields = $this->loader->resultStep->getFields(array($idField, $key));

        foreach ($fields as $offset => $itemData) {
            $id = $itemData->$idField;
            $ownerId = $itemData->$key;
            $this->idOffsets[$id] = $offset;
            $this->map[$ownerId] = $id;
        }
    }

    public function getMappedFor($owner)
    {
        $item = parent::getMappedFor($item);
        $itemProperty = $this->config->itemProperty;
        $item->$itemProperty->setValue($owner);
    }
}
