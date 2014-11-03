<?php

namespace PHPixie\ORM\Conditions\Condition\Group;

class Relationship extends \PHPixie\ORM\Conditions\Condition\Group
{
    protected $relationship;

    public function __construct($relationship)
    {
        $this->relationship = $relationship;
    }

    public function setRelationship($relationship)
    {
        $this->relationship = $relationship;
    }

    public function relationship()
    {
        return $this->relationship;
    }

}
