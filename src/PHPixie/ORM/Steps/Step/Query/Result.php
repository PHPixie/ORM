<?php

namespace PHPixie\ORM\Steps\Step\Query;

abstract class Result extends \PHPixie\ORM\Steps\Step\Query 
                      implements \PHPixie\ORM\Steps\Result
{
    protected $result;
    
    public function execute()
    {
        $this->result = $this->query->execute();

        if ($this->result === null)
            throw new \PHPixie\ORM\Exception\Plan("Query did not return a result.");
    }

    public function result()
    {
        if ($this->result === null)
            throw new \PHPixie\ORM\Exception\Plan("This plan step has not been executed yet.");

        return $this->result;
    }

    public function getField($field, $skipNulls = true)
    {
        $values = array();
        $result = $this->result();
        foreach($this as $item) {
            $value = $result->getItemField($item, $field);
            if ($value !== null || !$skipNulls) {
                $values[] = $value;
            }
        }
        
        return $values;
    }
    
    public function getFields($fields)
    {
        $data = array();
        $result = $this->result();
        foreach($this as $item){
            $values = array();
            foreach($fields as $field) {
                $values[$field] = $result->getItemField($item, $field);
            }
            $data[]=$values;
        }
        
        return $data;
    }

    abstract public function getIterator();
    abstract public function asArray();
}
