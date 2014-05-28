<?php

namespace PHPixie\ORM\Loaders\Loader\Preloadable\Repository;

class Iterator extends \PHPixie\ORM\Loaders\Loader\Preloadable\Repository
{
    protected $iterator;
    protected $currentOffset = null;
    protected $currentModel = null;
    protected $reachedEnd = false;

    public function __construct($loaders, $repository, $iterator)
    {
        parent::__construct($loaders, $repository);
        $this->iterator = $iterator;
    }

    public function offsetExists($offset)
    {
        return !$this->reachedEnd;
    }

    public function getModelByOffset($offset)
    {
        if ($this->currentOffset === $offset)
            return $this->currentModel;

        if ($this->currentOffset === null && $offset === 0) {
            $this->currentOffset = 0;

        } elseif ($this->currentOffset + 1 === $offset) {

            $this->iterator->next();
            if (!$this->iterator->valid()) {
                $this->reachedEnd = true;

                throw new \PHPixie\ORM\Exception\Loader("Offset $offset doesn't exist.");
            }
            $this->currentOffset++;

        }else
            throw new \PHPixie\ORM\Exception\Loader("Models can only be accessed in sequential order when using this loader.");

        $data = $this->iterator->current();
        $this->currentModel = $this->loadModel($data);

        return $this->currentModel;
    }

    public function iterator()
    {
        return $this->iterator;
    }
}
