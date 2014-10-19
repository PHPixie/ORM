<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Property;

abstract class Model extends \PHPixie\ORM\Relationships\Type\Embedded\Property\Model
                    implements \PHPixie\ORM\Relationships\Relationship\Property\Model\Data
{
    protected $handler;

    abstract public function asData($recursive = false);
}
