<?php

namespace PHPixie\ORM\Steps\Step\Query\Result;

class Iterator extends \PHPixie\ORM\Steps\Step\Query\Result
{
    public function getIterator()
    {
        return $this->result();
    }
    
    public function asArray()
    {
        return $this->result()->asArray();
    }
}
