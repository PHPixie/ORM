<?php

namespace PHPixie\ORM\Relationships\Relationship;

interface Preloader
{
    public function loadProperty($property);
}
