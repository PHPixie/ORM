<?php

namespace PHPixie\ORM\Models\Model;

class Database extends \PHPixie\ORM\Models\Model
{
    protected $drivers;
    
    public function __construct($drivers, $wrappers)
    {
        $this->drivers = $drivers;
        parent::__construct($wrappers);
    }
    
    public function repository($database)
    {
        
    }
}