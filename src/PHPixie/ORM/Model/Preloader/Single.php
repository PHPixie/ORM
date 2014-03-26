<?php

namespace PHPixie\ORM\Model\Preloader;

abstract class Single extends \PHPixie\ORM\Model\Preloader
{
    protected $map;

    public function loadFor($owner)
    {
        if ($this->items === null)
            $this->processItems();

        $id = $this->getItemId($owner);

        return $this->getModel($id);
    }

    protected function processItems();

}
