<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation;

abstract class Preloader implements \PHPixie\ORM\Relationships\Relationship\Preloader
{
    abstract public function loadProperty($property);
}
