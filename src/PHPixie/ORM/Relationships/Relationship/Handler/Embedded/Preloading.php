<?php

namespace PHPixie\ORM\Relationships\Relationship\Handler\Embedded;

interface Preloading extends \PHPixie\ORM\Relationships\Relationship\Handler
{
    public function mapPreloadEmbedded($side, $property, $result, $plan, $embeddedPath);
}