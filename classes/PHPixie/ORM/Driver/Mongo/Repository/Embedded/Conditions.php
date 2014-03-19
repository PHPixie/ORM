<?php

namespace PHPixie\ORM\Driver\Mongo\Repository\Embedded;

class Conditions extends \PHPixie\DB\Conditions
{
    public function builder($defaultOperator = '=')
    {
        return new Conditions\Builder($this, $defaultOperator);
    }
}