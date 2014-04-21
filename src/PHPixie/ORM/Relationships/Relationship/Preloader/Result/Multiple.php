<?php

namespace PHPixie\ORM\Model\Preloader;

abstract class Multiple extends \PHPixie\ORM\Model\Preloader
{
    protected $map = array();

    public function getMappedFor($owner)
    {
        $ids = $this->itemIdsFor($owner);

        return $this->buildLoader($ids);
    }

    protected function buildLoader($ids)
    {
        return $this->loaders->preloader($this, $ids);
    }

    protected function itemIdsFor($model)
    {
        return $this->map[$model->id()];
    }
}
