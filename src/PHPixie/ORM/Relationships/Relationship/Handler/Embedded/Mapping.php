<?php

namespace PHPixie\ORM\Relationships\Relationship\Handler\Embedded;

interface Mapping extends \PHPixie\ORM\Relationships\Relationship\Handler
{
    public function mapSubdocument($$builder, $side, $group, $plan, $embeddedPath);
}