<?php

namespace PHPixie\ORM\Model\Data\Data\Document;

class Diff
{
    protected $set;
    protected $unset;

    public function __construct($set, $unset)
    {
        $this->set = $set;
        $this->unset = $unset;
    }

    public function set()
    {
        return $this->set;
    }

    public function unset()
    {
        return $this->unset;
    }

}
