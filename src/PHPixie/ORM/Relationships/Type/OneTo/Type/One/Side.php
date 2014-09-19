<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\One;

class Side extends \PHPixie\ORM\Relationships\Type\OneTo\Side
{
    public function relationshipType()
    {
        return 'oneToOne';
    }
}
