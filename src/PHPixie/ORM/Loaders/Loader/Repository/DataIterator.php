<?php

namespace PHPixie\ORM\Loaders\Loader\Repository;

class DataIterator extends \PHPixie\ORM\Loaders\Loader\Repository
{
    protected $dataIterator;
    protected $currentOffset = null;
    protected $currentModel = null;
    protected $reachedEnd = false;

    public function __construct($loaders, $repository, $dataIterator)
    {
        parent::__construct($loaders, $repository);
        $this->dataIterator = $dataIterator;
    }

    public function offsetExists($offset)
    {
        return !$this->reachedEnd;
    }

    public function getByOffset($offset)
    {
        if ($this->currentOffset === $offset)
            return $this->currentModel;

        if ($this->currentOffset === null && $offset === 0) {
            $this->currentOffset = 0;

        } elseif ($this->currentOffset + 1 === $offset) {

            $this->dataIterator->next();
            if (!$this->dataIterator->valid()) {
                $this->reachedEnd = true;

                throw new \PHPixie\ORM\Exception\Loader("Offset $offset doesn't exist.");
            }
            $this->currentOffset++;

        }else
            throw new \PHPixie\ORM\Exception\Loader("Models can only be accessed in sequential order when using this loader.");

        $data = $this->dataIterator->current();
        $this->currentModel = $this->loadModel($data);

        return $this->currentModel;
    }

    public function dataIterator()
    {
        return $this->dataIterator;
    }
}
