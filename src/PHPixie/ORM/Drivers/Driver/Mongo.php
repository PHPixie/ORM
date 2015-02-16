<?php

namespace PHPixie\ORM\Drivers\Driver;

class Mongo extends \PHPixie\ORM\Drivers\Driver
{
    public function config($modelName, $configSlice)
    {
        return new Mongo\Config(
            $this->configs->inflector(),
            $modelName,
            $configSlice
        );
    }
    
    public function repository($config)
    {
        return new Mongo\Repository(
            $this->models->database(),
            $this->database,
            $this->data,
            $config
        );
    }
    
    public function query($config)
    {
        return new Mongo\Query(
            $this->values,
            $this->mappers->query(),
            $this->maps->queryProperty(),
            $this->conditions->container($config->model),
            $config
        );
    }

    public function entity($repository, $data, $isNew)
    {
        return new Mongo\Entity(
            $this->maps->entityProperty(),
            $repository,
            $data,
            $isNew
        );
    }
}
