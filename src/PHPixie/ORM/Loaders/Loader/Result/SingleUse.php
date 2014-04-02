<?php

namespace PHPixie\ORM\Loaders\Loader\Result;

class SingleUse extends \PHPixie\ORM\Loaders\Loader\Result
{
    protected $resultIterator;
    protected $currentOffset = null;
    protected $currentModel = null;
    protected $reachedEnd = false;
    
    public function __construct($loaders, $repository, $resultStep)
    {
        parent::__construct($loaders, $repository);
        $this->resultIterator = $resultStep->getIterator();
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
        
        }elseif ($this->currentOffset + 1 === $offset) {
            
            $this->resultIterator->next();
            if (!$this->resultIterator->valid()){
                $this->reachedEnd = true;
                return null;
            }
            $this->currentOffset++;
            
        }else
            throw new \PHPixie\ORM\Exception\Loader("Models can only be accessed in sequential order when using this loader.");
        
        $data = $this->resultIterator->current();
        $this->currentModel = $this->loadModel($data);
        return $this->currentModel;
    }
    
    public function currentModel()
    {
        return $this->currentModel;
    }
}