<?php

namespace PHPixie\ORM\Relationship\Types\Embedded\Type\Embeds\Many;

class Preloader extends \PHPixie\ORM\Relationship\Type\Preloader
{
    public function loadProperty($property)
    {
        $this->loader->requireLoadedItems();
    }
}