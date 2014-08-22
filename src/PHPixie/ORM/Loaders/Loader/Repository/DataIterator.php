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
        if($this->currentOffset === $offset)
            return true;
        
        $isFirst = $this->currentOffset === null;
        if (!($this->currentOffset + 1 === $offset || $isFirst && $offset === 0))
            throw new \PHPixie\ORM\Exception\Loader("Models can only be accessed in sequential order when using this loader.");
        
        if(!$isFirst)
            $this->dataIterator->next();

        $this->currentModel = null;
        if (!$this->dataIterator->valid()) {
            $this->reachedEnd = true;
            return false;
        }
        
        if($isFirst) {
            $this->currentOffset = 0;
        }else{
            $this->currentOffset++;
        }
            
        return true;
    }

    public function getByOffset($offset)
    {
        if(!$this->offsetExists($offset))
            throw new \PHPixie\ORM\Exception\Loader("Offset $offset does not exist.");
        
        if($this->currentModel === null) {
            $data = $this->dataIterator->current();
            $this->currentModel = $this->loadModel($data);
        }
        
        return $this->currentModel;
    }

    public function dataIterator()
    {
        return $this->dataIterator;
    }
}
