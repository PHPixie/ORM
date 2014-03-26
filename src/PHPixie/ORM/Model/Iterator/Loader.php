<?php

namespace PHPixie\ORM\Model\Iterator;

class Loader extends \PHPixie\ORM\Model\Iterator implements \Countable
{
    protected $loader;
    protected $dataIterator;
    protected $currentModel;

    public function __construct($loader, $dataIterator)
    {
        $this->loader = $loader;
        $this->dataIterator = $dataIterator;
    }

    public function current()
    {
        if ($this->currentModel === null)
            $this->currentModel = $this->loader->load($this->dataIterator->current());

        return $this->currentModel;
    }

    public functiom

}
