<?php

namespace PHPixie\ORM\Loaders;

class SingleUse extends \PHPixie\ORM\Loaders\Reusable
{
    protected $resultIterator;
    protected $currentOffset = null;
    protected $currentModel = null;
    protected $reachedEnd = false;
    
    public function __construct($repository, $resultStep)
    {
        parent::__construct($orm, $repository, $preloaders);
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
            throw new \PHPixie\ORM\Exception\Loader("Models may only be accessed in sequential order when using this loader.");
        
        $data = $this->resultIterator->current();
        $this->currentModel = $this->loadModel($data);
        return $this->currentModel;
    }
}