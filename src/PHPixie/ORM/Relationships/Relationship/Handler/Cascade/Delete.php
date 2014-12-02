<?php

namespace PHPixie\ORM\Relationships\Relationship\Handler;

interface Delete extends \PHPixie\ORM\Relationships\Relationship\Handler
{
    public function handleDelete($side, $reusableResult, $plan, $sidePath);
}
