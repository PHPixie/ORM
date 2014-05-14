<?php

namespace PHPixie\ORM\Steps\Step;

class Query extends \PHPixie\ORM\Steps\Step
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
        return $this->query;
    }

    public function usedConnections()
    {
        return array($this->query->connection());
    }
}
