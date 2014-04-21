<?php

namespace PHPixie\ORM\Relationships\Types\Embedded\Type;

abstract class Embeds extends \PHPixie\ORM\Relationships\Relationship\Type
{
	abstract public function loader($config, $ownerLoader);
	abstract public function preloader($side, $loader);
}