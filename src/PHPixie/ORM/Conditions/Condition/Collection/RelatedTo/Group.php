<?php

namespace PHPixie\ORM\Conditions\Condition\Collection\RelatedTo;

class Group extends    \PHPixie\ORM\Conditions\Condition\Collection\Group
            implements \PHPixie\ORM\Conditions\Condition\Collection\RelatedTo
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
