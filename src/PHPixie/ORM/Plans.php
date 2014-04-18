<?php

namespace PHPixie\ORM;

class Plans
{
    public function plan()
    {
        return new ORM\Plans\Plan();
    }
    
    public function plan()
    {
        return new ORM\Plans\Plan\Loader($this);
    }
}