<?php

namespace PHPixie\ORM\Model\Preloader;

abstract class Multiple extends \PHPixie\ORM\Model\Preloader
{
    protected $map;

    public function loadFor($owner)
    {
        if ($this->items === null)
            $this->processItems();

        $ids = $this->getItemIds($owner);

        return $this->iterator($ids);
    }

    public function iterator($ids)
    {
        return Iterator\Iterator($this, $ids)
    }

    protected function getItemIds($owner);
    protected function processItems();
}
