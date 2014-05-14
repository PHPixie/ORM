<?php

namespace PHPixie\ORM\Steps;

abstract class Step
{
    public function usedConnections()
    {
        return array();
    }
    
    abstract public function execute();
}
