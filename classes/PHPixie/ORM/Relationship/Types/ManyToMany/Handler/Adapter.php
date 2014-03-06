<?php

namespace \PHPixie\ORM\Relationships\OneToMany\Handler;

abstract class Adapter
{
    abstract protected function setRepository($query, $repository);

    abstract protected function pivotStrategy($side, $config);

    abstract protected function setCollection($query, $collection);
}
