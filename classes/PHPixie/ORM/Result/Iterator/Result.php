<?php

namespace PHPixie\ORM\Result\Iterator;

class Result extends \PHPixie\ORM\Result\Iterator
{
    protected $preloaders;
    protected $current;

    public function __construct($preloaders)
    {
        $this->preloaders = $preloaders;
    }

    public function current()
    {
        if ($this->currentModel === null)
            $this->currentModel();

        return $this->currentModel;
    }

    abstract public function key();
    abstract public function valid();
    abstract public function next();
    abstract public function rewind();
    abstract protected function currentModel();
}
