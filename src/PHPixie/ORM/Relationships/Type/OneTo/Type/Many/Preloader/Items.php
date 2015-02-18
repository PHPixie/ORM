<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader;

class Items extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result\Multiple\IdMap
{
    protected function mapItems()
    {
        $idField  = $this->modelConfig->idField;
        $ownerKey = $this->side->config()->ownerKey;

        $fields = $this->result->getFields(array($idField, $ownerKey));
        foreach ($fields as $offset => $itemData) {
            $id = $itemData[$idField];
            $ownerId = $itemData[$ownerKey];
            $this->idOffsets[$id] = $offset;
            $this->pushToMap($ownerId, $id);
        }
    }
}
