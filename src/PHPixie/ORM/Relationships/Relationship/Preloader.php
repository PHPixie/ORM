<?php

namespace PHPixie\ORM\Relationships\Relationship;

interface Preloader
{
    public function loader();
    public function loadProperty($property);
}
