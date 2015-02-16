<?php

namespace PHPixie\ORM\Relationships\Relationship\Side;

interface Relationship extends \PHPixie\ORM\Relationships\Relationship\Side
{
    public function relatedModelName();
}