<?php

namespace PHPixie\ORM\Relationships\Relationship\Side\Cascade;

interface Delete extends \PHPixie\ORM\Relationships\Relationship\Side
{
    public function isDeleteHandled();
}