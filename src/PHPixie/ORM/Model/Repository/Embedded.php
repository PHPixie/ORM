<?php

namespace PHPixie\ORM\Model\Repository

abstract class Embedded extends \PHPixie\ORM\Repositories\Repository
{
    public function save($model)
    {
        throw new \PHPixie\ORM\Exception\Mapper("Embedded models cannot be saved individually. Save the root model instead.");
    }
	
    public function delete($model)
    {
        throw new \PHPixie\ORM\Exception\Mapper("Instead of deleting an embedded model remove it from its owner using its relationship.");
    }
	
    public function query()
    {
        throw new \PHPixie\ORM\Exception\Mapper("Embedded models cannot be queried directly.");
    }
}
