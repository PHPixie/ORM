<?php

namespace PHPixie\ORM\Relationships\Type;

abstract class Embeds extends \PHPixie\ORM\Relationships\Relationship\Implementation
{
    abstract public function preloader();
    abstract public function preloadResult($reusableResult, $path);
}
