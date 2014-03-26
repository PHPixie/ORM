<?php

namespace PHPixie\ORM\Model\Preloader\Multiple;

class Iterator extends \PHPixie\ORM\Model\Iterator implements \Countable
{
    protected $preloader;
    protected $ids;
    protected $currentModel;

    public function __construct($preloader, $ids)
    {
        $this->preloader = $preloader;
        $this->ids = $ids;
        $this->count = count($ids);
    }

    public function current()
    {
        if (!$this->valid())
            return null;

        if ($this->currentModel === null)
            $this->currentModel = $this->preloader->getModel(current($ids));

        return $this->currentModel;
    }

    public function key()
    {
        key($this->ids);
    }

    public function valid()
    {
        return !$this->reachedEnd;
    }

    public function next()
    {
        if ($this->key() === $count - 1) {
            $this->reachedEnd = true;
        } else {
            next($this->ids);
            $this->currentModel = null;
        }
    }

    public function rewind()
    {
        $this->reachedEnd = false;
        reset($this->ids);
    }

    public function count()
    {
        return $this->count;
    }
}
