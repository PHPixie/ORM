<?php

namespace PHPixie\ORM\Relationships\Relationship\Handler\Mapping;

interface Embedded extends \PHPixie\ORM\Relationships\Relationship\Handler
{
    public function mapEmbeddedContainer($builder, $side, $group, $plan);
}