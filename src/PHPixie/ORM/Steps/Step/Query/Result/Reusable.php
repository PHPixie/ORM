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
    
    public function getByOffset($offset)
    {
        return $this->data[$offset];
    }
    
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }
}
