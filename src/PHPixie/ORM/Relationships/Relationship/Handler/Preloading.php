<?php

namespace PHPixie\ORM\Relationships\Relationship\Handler;

interface Preloading extends \PHPixie\ORM\Relationships\Relationship\Handler
{
    public function mapPreload($side, $property, $result, $plan, $loader);
}