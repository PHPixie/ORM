<?php

namespace PHPixie\ORM\Driver\Mongo\Repository;

class Embedded extends \PHPixie\ORM\Model\Repository\Embedded
{
    public function loadModel($document)
    {
        return $this->embedded->model();
    }
}
