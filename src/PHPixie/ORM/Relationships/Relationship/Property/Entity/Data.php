<?php

namespace PHPixie\ORM\Relationships\Relationship\Property\Entity;

interface Data extends \PHPixie\ORM\Relationships\Relationship\Property\Entity {
    public function asData($recursive = false);
}