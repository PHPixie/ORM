<?php

namespace PHPixie\ORM\Driver;

class Mongo extends \PHPixie\Mongo\Driver
{
    public function repository($modelName, $modelConfig)
    {
        $type = $modelConfig->get('type', 'collection');
        $class = 'Mongo\Repository\\'.$type;
        return new $class($this->orm, $modelName, $modelConfig);
    }
}
