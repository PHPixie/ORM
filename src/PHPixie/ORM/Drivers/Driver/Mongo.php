<?php

namespace PHPixie\ORM\Drivers\Driver;

class Mongo extends \PHPixie\ORM\Drivers\Driver
{
    public function repository($modelName, $modelConfig)
    {
        if ($modelConfig->get('type', null) === 'embedded')
            return $this->embeddedRepository($modelName, $modelConfig);

        return $this->databaseRepository($modelName, $modelConfig);
    }

    protected function databaseRepository($modelName, $modelConfig)
    {
        return Mongo\Repository\Database($this->orm, $this, $modelName, $modelConfig);
    }

    protected function embeddedRepository($modelName, $modelConfig)
    {
        return Mongo\Repository\Embedded($this->orm, $this, $modelName, $modelConfig);
    }

    public function databaseModel($repository, $data, $isNew = true)
    {
        $relationshipMap = $this->ormBuilder->relationshipMap();

        return Mongo\Repository\Database\Model($relationshipMap, $repository, $data, $isNew);
    }

}
