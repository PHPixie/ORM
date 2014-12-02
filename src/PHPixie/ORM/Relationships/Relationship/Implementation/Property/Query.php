<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation\Property;

abstract class Query extends \PHPixie\ORM\Relationships\Relationship\Implementation\Property
                      implements \PHPixie\ORM\Relationships\Relationship\Property\Query
{
    protected $query;

    public function __construct($handler, $side, $query)
    {
        parent::__construct($handler, $side);
        $this->query = $query;
    }

    public function __invoke()
    {
        return $this->query();
    }

    abstract public function query();
}