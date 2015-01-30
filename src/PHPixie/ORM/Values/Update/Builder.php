<?php

namespace PHPixie\ORM\Values\Update;

class Builder extends \PHPixie\ORM\Values\Update
{
    protected $query;
    
    public function __construct($values, $query)
    {
        parent::__construct($values);
        $this->query = $query;
    }
    
    public function plan()
    {
        return $this->query->planUpdateValue($this);
    }
    
    public function execute()
    {
        $this->query->planUpdateValue($this)->execute();;
    }
}