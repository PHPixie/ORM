<?php

namespace PHPixie\ORM\Values\Updates;

interface Update
{
    public function set($values);
    public function getSet($values);
    public function clearSet($values);
    
    public function plan();
    public function execute();
}