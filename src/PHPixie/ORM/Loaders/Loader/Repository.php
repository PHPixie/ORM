<?php

namespace PHPixie\ORM\Loaders\Loader;

abstract class Repository extends \PHPixie\ORM\Loaders\Loader
{
    protected $repository;

    public function __construct($loaders, $repository)
    {
        parent::__construct($loaders);
        $this->repository = $repository;
    }

    protected function loadEntity($data)
    {
        return $this->repository->load($data);
    }
}
