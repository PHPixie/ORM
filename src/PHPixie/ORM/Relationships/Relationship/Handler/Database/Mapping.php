<?php

namespace PHPixie\ORM\Relationships\Relationship\Handler\Database;

interface Mapping extends \PHPixie\ORM\Relationships\Relationship\Handler
{
    public function mapQuery($query, $side, $group, $plan);
}