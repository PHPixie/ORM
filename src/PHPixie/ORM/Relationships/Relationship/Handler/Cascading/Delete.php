<?php

namespace PHPixie\ORM\Relationships\Relationship\Handler\Cascading;

interface Delete extends \PHPixie\ORM\Relationships\Relationship\Handler
{
    public function handleDelete($side, $reusableResult, $plan, $sidePath);
}
