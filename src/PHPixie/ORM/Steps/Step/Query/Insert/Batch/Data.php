<?php

namespace PHPixie\ORM\Steps\Step\Query\Insert\Batch;

abstract class Data extends \PHPixie\ORM\Steps\Step
{
    protected $data;
    
    public function data()
    {
        if ($this->data === null) {
            throw new \PHPixie\ORM\Exception\Plan("This step has not been executed yet");
        }

        return $this->data;
    }
    
    abstract public function fields();
}