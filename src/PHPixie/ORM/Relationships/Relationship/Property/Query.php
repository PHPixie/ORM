<?php

namespace PHPixie\ORM\Relationships\Relationship\Property;

interface Query extends \PHPixie\ORM\Relationships\Relationship\Property
{
    public function __invoke();
    public function query();
}
