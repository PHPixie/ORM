<?php

namespace PHPixie\ORM\Loaders\Loader\Repository;

abstract class Embedded extends \PHPixie\ORM\Loaders\Loader\Repository
{
    protected $owner;

    public function __construct($loaders, $repository, $owner)
    {
        parent::__construct($loaders, $repository);
        $this->owner = $owner;
    }

    protected function loadModel($data)
    {
        return $this->repository->load($owner, $data);
    }
}
