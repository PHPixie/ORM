<?php

namespace PHPixie\ORM\Relationships\Relationship\Preloader\Result;

abstract class Single extends \PHPixie\ORM\Model\Preloader
{
    public function getMappedFor($model)
    {
        $id = $this->map[$model->id()];

        return $this->getModel($id);
    }
}
