<?php

namespace PHPixie\ORM\Loaders;

class Iterator implements \Iterator
{
    protected $loader;
    protected $offset = 0;
    protected $reachedEnd = false;

    public function __construct($loader)
    {
        $this->loader = $loader;
    }

    public function key()
    {
        return $this->offset;
    }

    public function current()
    {
        return $this->loader->getByOffset($this->offset);
    }

    public function rewind()
    {
        $this->offset = 0;
        $this->reachedEnd = false;
    }

    public function valid()
    {
        if($this->reachedEnd)
            return false;
        
        return $this->loader->offsetExists($this->offset);
    }

    public function next()
    {
        if($this->loader->offsetExists($this->offset+1)){
            $this->offset++;
        }else{
            $this->reachedEnd = true;
        }
    }
}