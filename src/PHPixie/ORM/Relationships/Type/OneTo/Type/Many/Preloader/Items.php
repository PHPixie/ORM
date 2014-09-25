<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader;

class Items extends \PHPixie\ORM\Relationships\Relationship\Preloader\Result\Multiple\IdMap
{
    protected function mapItems()
    {
        $idField = $this->loader->repository()->idField();
        $ownerKey = $this->side->config()->ownerKey;

        $fields = $this->loader->reusableResult()->getFields(array($idField, $ownerKey));
        foreach ($fields as $offset => $itemData) {
            $id = $itemData[$idField];
            $ownerId = $itemData[$ownerKey];
            $this->idOffsets[$id] = $offset;
            $this->pushToMap($ownerId, $id);
        }
    }
}
