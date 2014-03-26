<?php

namespace \PHPixie\ORM\Relationships\ManyToMany\Handler\Side;

class Left
{
    protected $side = 'left';

    public function addLeftSideConditions($group, $rightHandler, $pivotHandler, $config, $query, $plan)
    {
        $this->addOpposingSideConditions($group, 'right', $leftHandler, $pivotHandler, $config, $query, $plan);
    }

}
