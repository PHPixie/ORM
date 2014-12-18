<?php

namespace PHPixie\ORM\Relationships\Relationship\Type;

interface Database extends \PHPixie\ORM\Relationships\Relationship
{
    public function queryProperty($side, $query);
}