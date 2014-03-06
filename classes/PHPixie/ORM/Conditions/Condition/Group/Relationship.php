<?php

namespace PHPixie\ORM\Conditions\Condition\Group;

class Relationship extends \PHPixie\ORM\Conditions\Condition\Group
{
    public $relationship;

    public function __construct($relationship)
    {
        $this->relationship = $relationship;
    }

}
