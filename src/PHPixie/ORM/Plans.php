<?php

namespace PHPixie\ORM;

class Plans
{
    public function step()
    {
        return new ORM\Plans\Plan\Step();
    }
    
    public function loader()
    {
        return new ORM\Plans\Plan\Loader($this);
    }
}