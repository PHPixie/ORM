<?php

namespace PHPixie\ORM\Conditions\Condition\Field;

class Subquery extends    \PHPixie\Database\Conditions\Condition\Field\Implementation
{
    protected $field;
    protected $subquery;
    protected $subqueryField;
    
    public function __construct($field, $subquery, $subqueryField)
    {
        parent::__construct($field);
        $this->subquery      = $subquery;
        $this->subqueryField = $subqueryField;
    }
    
    public function subquery()
    {
        return $this->subquery;
    }
    
    public function setSubquery($subquery)
    {
        $this->subquery = $subquery;
        return $this;
    }
    
    public function subqueryField()
    {
        return $this->subqueryField;
    }
    
    public function setSubqueryField($field)
    {
        $this->subqueryField = $field;
        return $this;
    }
}
