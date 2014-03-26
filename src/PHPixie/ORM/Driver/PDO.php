<?php

namespace PHPixie\ORM\Driver;

class PDO extends \PHPixie\PDO\Driver
{
    public function repository($modelName, $modelConfig)
    {
        return new PDO\Repository($this->orm, $modelName, $modelConfig);
    }

}
