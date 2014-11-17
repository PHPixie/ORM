<?php

namespace PHPixie\ORM\Loaders\Loader\Repository;

class ReusableResultStep extends \PHPixie\ORM\Loaders\Loader\Repository
{
    protected $repository;
    protected $reusableResultStep;

    public function __construct($loaders, $repository, $reusableResultStep)
    {
        parent::__construct($loaders, $repository);
        $this->reusableResultStep = $reusableResultStep;
    }

    public function offsetExists($offset)
    {
        return $this->reusableResultStep->offsetExists($offset);
    }

    public function getByOffset($offset)
    {
        $data = $this->reusableResultStep->getByOffset($offset);
        return $this->loadModel($data);
    }

    public function reusableResultStep()
    {
        return $this->reusableResultStep;
    }

}