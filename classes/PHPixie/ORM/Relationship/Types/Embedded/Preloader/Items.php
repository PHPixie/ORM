<?php

namespace PHPixie\ORM\Relationships\OneToMany\Preloader;

class Items extends \PHPixie\ORM\Model\Preloader\Multiple
{
    protected function processItems()
    {
        $this->items = array();
        $this->idsMap = array();
        $idField = $this->repository->idField();
        $key = $this->link->config()->itemKey;
        foreach ($this->reusableResultStep->iterator() as $itemData) {
            $id = $itemData->$idField;
            $this->items[$id] = $itemData;
            $ownerId = $itemData->$key;
            if (!isset($this->idsMap[$ownerId]))
                $this->idsMap[$ownerId] = array();
            $this->idsMap[$ownerId][] = $id;
        }
    }

    protected function getItemIds($owner)
    {
        return $this->idsMap[$owner->id()];
    }

}
