<?php

namespace PHPixie\ORM;

class Result impelements \Iterator
{
    protected $repository;
    protected $dataIterator;
    protected $current;
    protected $preloaders = array();

    public function __construct($repository, $dataIterator)
    {
        $this->repository = $repository;
        $this->dataIterator = $dataIterator;
    }

    public function current()
    {
        if ($this->currentModel === null)
            $this->currentModel = $this->loadModel($this->dataIterator->current());

        return $this->currentModel;
    }

    public function key()
    {
        return $this->dataIterator->key();
    }

    public function valid()
    {
        return $this->dataIterator->valid();
    }

    public function next()
    {
        $this->dataIterator->next();
        $this->currentModel = null;
    }

    public function rewind()
    {
        $this->dataIterator->rewind();
        $this->currentModel = null;
    }

    protected function loadModel($data)
    {
        $model = $this->repository->loadModel($data);
        foreach($this->preloaders as $preloader)
            $preloader->preloadFor($model);
    }

    public function
}
