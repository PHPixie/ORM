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
        $model = $this->repository->loadFromDocument($data);
        $model->setOwnerRelationship($this->owner, $this->ownerPropertyName);
        return $model;
    }
}
