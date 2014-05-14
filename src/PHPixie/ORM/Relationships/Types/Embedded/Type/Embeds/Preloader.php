<?php

namespace PHPixie\ORM\Relationships\Types\Embedded\Type\Embeds;

class Preloader extends \PHPixie\ORM\Relationships\Relationship\Preloader
{
    public function loadProperty($property)
    {
        $this->loader->requireLoadedItems();
    }
}
