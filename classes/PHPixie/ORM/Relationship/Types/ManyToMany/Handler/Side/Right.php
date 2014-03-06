<?php

namespace \PHPixie\ORM\Relationships\ManyToMany\Handler\Side;

class Right
{
    protected $side = 'right';

    public function addLeftSideConditions($group, $leftHandler, $pivotHandler, $config, $query, $plan)
    {
        $this->addOpposingSideConditions($group, 'left', $leftHandler, $pivotHandler, $config, $query, $plan);
    }

}
