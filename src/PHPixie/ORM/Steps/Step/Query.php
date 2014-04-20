<?php

namespace PHPixie\ORM\Query\Plan\Step;

class Query extends \PHPixie\ORM\Query\Plan\Step
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function execute()
    {
        $this->query->execute();
    }

    public function query()
    {
        return $this->query();
    }
}
