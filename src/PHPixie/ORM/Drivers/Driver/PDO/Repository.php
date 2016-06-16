<?php

namespace PHPixie\ORM\Drivers\Driver\PDO;

class Repository extends \PHPixie\ORM\Drivers\Driver\SQL\Repository
{
    protected $fieldList;
    
    public function fieldList()
    {
        if($this->fieldList === null) {
            $this->fieldList = $this->connection()->listColumns($this->config->table);
        }
        
        return $this->fieldList;
    }
}
