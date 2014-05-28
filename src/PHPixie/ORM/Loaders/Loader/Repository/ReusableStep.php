<?php

namespace PHPixie\ORM\Loaders\Loader\Preloadable\Repository;

class ReusableStep extends \PHPixie\ORM\Loaders\Loader\Preloadable\Repository
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
        $data = $this->reusableResultStep->offsetExists($offset);
    }

    public function getModelByOffset($offset)
    {
        $data = $this->reusableResultStep->getByOffset($offset);
        return $this->loadModel($data);
    }

    public function resultStep()
    {
        return $this->reusableResultStep;
    }

}