<?php

namespace PHPixie\ORM\Model\Repository

abstract class Embedded extends \PHPixie\ORM\Repositories\Repository
{
    public function save($model)
    {
        throw new \PHPixie\ORM\Exception\Mapper("Embedded models cannot be saved individually. Save the root model instead.");
    }
}
