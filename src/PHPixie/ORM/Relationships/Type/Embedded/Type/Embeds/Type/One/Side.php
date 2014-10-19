<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One;

class Side extends \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Side
{
    public function relationshipType()
    {
        return 'embedsMany';
    }
}
