<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader;

class Items extends \PHPixie\ORM\Relationships\Relationship\Preloader\Result\Multiple
{
    protected $owners = array();
    protected $processedItems = array();

    public function getMappedFor($owner)
    {
        $this->owners[$owner->id()] = $owner;
        $ids = $this->itemIdsFor($owner);

        return $this->buildLoader($ids, $owner);
    }

    protected function mapItems()
    {
        $idField = $this->loader->repository()->idField();
        $itemKey = $this->side->config()->itemKey;

        $fields = $this->loader->resultStep->getFields(array($idField, $itemKey));

        foreach ($fields as $offset => $itemData) {
            $id = $itemData->$idField;
            $ownerId = $itemData->$key;
            $this->idOffsets[$id] = $offset;

            if (!isset($this->map[$ownerId]))
                $this->map[$ownerId] = array();

            $this->map[$ownerId][] = $id;
        }
    }

    protected function buildLoader($ids, $owner = null)
    {
        $loader = parent::buildLoader($ids);

        return $this->relationshipType()->ownerLoader($loader, $this->config->itemProperty, $owner);
    }

}
