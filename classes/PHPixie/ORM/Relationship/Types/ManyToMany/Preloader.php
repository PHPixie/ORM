<?php

namespace PHPixie\ORM\Relationships\ManyToMany;

class Preloader
{
    protected $repository;
    protected $result;
    protected $ownerField;
    protected $itemsMap;

    public function __construct($preloaders)
    {
        $this->repository = $repository;
        $this->result = $result;
        $this->ownerField = $ownerField;
    }

    public function set($owner, $property)
    {
        $property = $this->getProperty();
        $loader = $this->orm->loader($itemModel, $this->getItems($owner), $preloaders);
        $property->setLoader($loader);
    }

    protected function getItems($owner)
    {
        if ($this->itemsMap === null)
            $this->itemsMap = $this->buildItemsMap();

        return $this->itemsMap[$owner->id()];
    }

    protected function buildItemsMap()
    {
        $items = array();
        $idField = $this->repository->idField();
        foreach($this->dataResult as $row)
            $items[$row->$idField] = &$row;

        $itemsMap = array();
        $ownerKey = $this->ownerPivotKey;
        $itemKey = $this->pivotItemKey;

        foreach ($this->pivotResult as $row) {
            if (!isset($itemsMap[$row->$ownerKey]))
                $itemsMap[$row->ownerKey] = array();
            $itemsMap[$row->ownerKey][] = &$items[$row->$itemKey];
        }

        return $itemsMap;
    }
}
