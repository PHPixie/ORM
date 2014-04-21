<?php

namespace PHPixie\ORM\Steps\Step\Query\Result;

class Reusable extends \PHPixie\ORM\Steps\Step\Query\Result
{
    protected $data;

    protected function data()
    {
        if ($this->data === null)
            $this->data = $this->result()->asArray();

        return $this->data;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->data());
    }
}
