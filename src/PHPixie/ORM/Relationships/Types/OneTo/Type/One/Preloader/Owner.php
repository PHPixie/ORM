<?php

namespace PHPixie\ORM\Relationships\OneTo\Type\One\Preloader;

class Owner extends \PHPixie\ORM\Relationships\OneTo\Preloader\Owner
{
    public function getMappedFor($item)
    {
        $owner = parent::getMappedFor($item);
        $ownerProperty = $this->config->ownerProperty;
        $owner->ownerProperty->setValue($item);
    }
}
