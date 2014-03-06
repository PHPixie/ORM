<?php

namespace PHPixie\ORM\Query\Plan\Step\Query\Result;

class Single extends \PHPixie\ORM\Query\Plan\Step\Query\Result
{
    protected $iterator = null;

    public function iterator()
    {
        if ($this->iterator === null)
            $this->iterator = $this->orm->resultIteratorIterator($this->data());

        return $this->iterator;
    }
}
