<?php

namespace PHPixie\ORM\Relationships\Types\OneTo\Type\One;

class Side extends \PHPixie\ORM\Relationships\Types\OneTo\Side
{
    public function relationshipType()
    {
        return 'oneToOne'
    }
}
