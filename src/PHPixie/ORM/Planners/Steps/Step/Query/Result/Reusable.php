<?php

namespace PHPixie\ORM\Query\Plan\Step\Query\Result;

class Reusable extends \PHPixie\ORM\Query\Plan\Step\Query\Result
{
    protected $data;

    protected function data()
    {
        if ($this->data === null)
            $this->data = $this->result()->asArray();

        return $this->data;
    }

    public function iterator()
    {
        return $this->orm->resultDataIterator($this->data());
    }
}
