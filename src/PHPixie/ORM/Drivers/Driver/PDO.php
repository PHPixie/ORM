<?php

namespace PHPixie\ORM\Drivers\Driver;

class PDO extends \PHPixie\ORM\Drivers\Driver
{
    public function repository($modelName, $modelConfig)
    {
        return new PDO\Repository\Database($this->orm, $this, $modelName, $modelConfig);
    }

    public function model($repository, $data, $isNew = true)
    {
        $relationshipMap = $this->ormBuilder->relationshipMap();

        return PDO\Repository\Database\Model($relationshipMap, $repository, $data, $isNew);
    }

}
