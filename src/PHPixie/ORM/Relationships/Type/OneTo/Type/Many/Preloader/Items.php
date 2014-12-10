<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader;

class Items extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result\Multiple\IdMap
{
    protected function mapItems()
    {
        $repository = $this->loader->repository();
        $idField = $repository->config()->idField;
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
