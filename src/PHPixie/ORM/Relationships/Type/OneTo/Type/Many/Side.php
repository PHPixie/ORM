<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many;

class Side extends \PHPixie\ORM\Relationships\Type\OneTo\Side
{
    public function relationshipType()
    {
        return 'oneToMany';
    }
}
