<?php

namespace PHPixie\ORM\Data\Diff;

class Removing extends \PHPixie\ORM\Data\Diff
{
    protected $remove;

    public function __construct($set, $remove)
    {
        parent::__construct($set);
        $this->remove = $remove;
    }

    public function remove()
    {
        return $this->remove;
    }
}