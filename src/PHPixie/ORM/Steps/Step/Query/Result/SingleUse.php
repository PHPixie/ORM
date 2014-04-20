<?php

namespace PHPixie\ORM\Query\Plan\Step\Query\Result;

class SingleUse extends \PHPixie\ORM\Query\Plan\Step\Query\Result
{
    public function getIterator()
    {
        return $this->result();
    }
}
