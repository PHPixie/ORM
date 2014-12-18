<?php

namespace PHPixie\ORM\Relationships;

interface Relationship
{
    public function getSides($config);
    public function handler();
    public function entityProperty($side, $model);
}
