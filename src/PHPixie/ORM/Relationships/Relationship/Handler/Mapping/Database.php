<?php

namespace PHPixie\ORM\Relationships\Relationship\Handler\Mapping;

interface Database extends \PHPixie\ORM\Relationships\Relationship\Handler
{
    public function mapDatabaseQuery($builder, $side, $group, $plan);
}