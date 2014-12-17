<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type\One;

class Side extends \PHPixie\ORM\Relationships\Type\Embeds\Side
{
    public function relationshipType()
    {
        return 'embedsOne';
    }
}
