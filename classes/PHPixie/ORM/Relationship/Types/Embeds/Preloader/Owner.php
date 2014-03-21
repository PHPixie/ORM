<?php

namespace PHPixie\ORM\Relationships\OneToMany\Preloader;

class Owner extends \PHPixie\ORM\Model\Preloader\Single
{
    protected function processItems()
    {
        $this->items = array();
        $this->idsMap = array();
        $idField = $this->repository->idField();
        $key = $this->side->config()->itemKey;
        foreach ($this->reusableResultStep->iterator() as $itemData) {
            $id = $itemData->$idField;
            $this->items[$id] = $itemData;
            $ownerId = $itemData->$key;
            if (!isset($this->idsMap[$ownerId]))
                $this->idsMap[$ownerId] = array();
            $this->idsMap[$ownerId][] = $id;
        }
    }

    protected function getItem($item)
    {
        $key = $this->side->config()->itemKey;

        return $this->idsMap[$item];
    }

}
