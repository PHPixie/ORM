<?php

namespace PHPixie\ORM\Drivers\Driver;

class PDO extends \PHPixie\ORM\Drivers\Driver
{
    public function config($modelName, $configSlice)
    {
        return new PDO\Config(
            $this->configs->inflector(),
            $modelName,
            $configSlice
        );
    }
    
    public function repository($config)
    {
        return new PDO\Repository(
            $this->models->database(),
            $this->database,
            $this->data,
            $config
        );
    }
    
    public function query($config)
    {
        return new PDO\Query(
            $this->values,
            $this->mappers->query(),
            $this->maps->queryProperty(),
            $this->conditions->container($config->model),
            $config
        );
    }

    public function entity($repository, $data, $isNew)
    {
        return new PDO\Entity(
            $this->maps->entityProperty(),
            $repository,
            $data,
            $isNew
        );
    }
}
