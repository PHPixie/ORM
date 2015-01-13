<?php

namespace PHPixie\ORM\Loaders\Loader\Repository;

class ReusableResult extends \PHPixie\ORM\Loaders\Loader\Repository
{
    protected $repository;
    protected $reusableResult;

    public function __construct($loaders, $repository, $reusableResult)
    {
        parent::__construct($loaders, $repository);
        $this->reusableResult = $reusableResult;
    }
    
    public function offsetExists($offset)
    {
        return $this->reusableResult->offsetExists($offset);
    }

    public function getByOffset($offset)
    {
        $data = $this->reusableResult->getByOffset($offset);
        return $this->loadEntity($data);
    }
    
    public function reusableResult()
    {
        return $this->reusableResult;
    }
}