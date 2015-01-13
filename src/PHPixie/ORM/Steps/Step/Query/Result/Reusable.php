<?php

namespace PHPixie\ORM\Steps\Step\Query\Result;

class Reusable extends \PHPixie\ORM\Steps\Step\Query\Result
               implements \PHPixie\ORM\Steps\Result\Reusable
{
    protected $data;

    public function asArray()
    {
        $this->ensureData();
        return $this->data;
    }

    public function getIterator()
    {
        $this->ensureData();
        return new \ArrayIterator($this->data);
    }
    
    public function getByOffset($offset)
    {
        $this->ensureData();
        return $this->data[$offset];
    }
    
    public function offsetExists($offset)
    {
        $this->ensureData();
        return array_key_exists($offset, $this->data);
    }
    
    protected function ensureData()
    {
        if ($this->data === null) {
            $this->data = $this->result()->asArray();
        }
    }
}
