<?php

namespace PHPixie\ORM\Relationships\Types\Embedded\Type\Embedsded\Type\Embeds\Type\Many;

class Preloader extends \PHPixie\ORM\Relationships\Relationship\Preloader
{
    public function loadProperty($property)
    {
        $this->loader->requireLoadedItems();
    }
}
