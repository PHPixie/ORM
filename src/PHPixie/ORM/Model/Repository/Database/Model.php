<?php

namespace PHPixie\ORM\Model\Repository\Database\Model

abstract class Model extends \PHPixie\ORM\Model
{
    public function id()
    {
        $idField = $this->repository->idField();
        
        if(isset($this->$idField))
            return $this->$idField;
        
        return null;
    }
}