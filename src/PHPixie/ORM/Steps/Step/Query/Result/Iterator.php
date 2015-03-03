<?php

namespace PHPixie\ORM\Steps\Step\Query\Result;

class Iterator extends \PHPixie\ORM\Steps\Step\Query\Result
{
    protected $iteratorUsed = false;
    
    public function getIterator()
    {
        if($this->iteratorUsed) {
            throw new \PHPixie\ORM\Exception\Plan("This iterator has already been used");
        }
        
        $this->iteratorUsed = true;
        return $this->result();
    }
    
    public function asArray()
    {
        return $this->getIterator()->asArray();
    }
}
