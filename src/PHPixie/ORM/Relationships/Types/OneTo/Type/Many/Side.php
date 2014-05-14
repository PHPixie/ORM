<?php

namespace PHPixie\ORM\Relationships\Types\OneTo\Type\Many;

class Side extends \PHPixie\ORM\Relationships\Types\OneTo\Side
{
    public function relationshipType()
    {
        return 'oneToMany'
    }
}
