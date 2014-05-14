<?php

namespace PHPixie\ORM\Loaders;

class Iterator
{
    protected $loader;
    protected $offset = 0;

    public function __construct($loader)
    {
        $this->loader = $loader;
    }

    public function offset()
    {
        return $this->offset;
    }

    public function current()
    {
        return $this->loader->getByOffset($this->key);
    }

    public function rewind()
    {
        $this->key = 0;
    }

    public function valid()
    {
        $this->loader->offsetExists($this->offset);
    }

    public function next()
    {
        $this->offset++;
    }
}
