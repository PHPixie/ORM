<?php

namespace PHPixie\ORM\Relationships\Relationship\Preloader\Result;

abstract class Single extends \PHPixie\ORM\Relationships\Relationship\Preloader\Result
{
    public function getMappedFor($model)
    {
        $id = $this->map[$model->id()];

        return $this->getModel($id);
    }
}
