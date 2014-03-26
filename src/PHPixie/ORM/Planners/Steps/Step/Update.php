<?php

namespace PHPixie\ORM\Query\Plan\Step;

class Update extends \PHPixie\ORM\Query\Plan\Step
{
    protected $query;
    protected $fieldSteps;

    public function __construct($query, $data)
    {
        $this->query = $query;
        $this->fieldSteps = $fieldSteps;
    }

    public function execute()
    {
        $data = array();
        foreach ($this->fieldSteps as $field => $step) {
            $value = $step->result()->getColumn();
            $count = count($value)

            if ($count > 1) {
                throw new \PHPixie\ORM\Exception\Plan("Update subquery returned more than one row");

            $data[$field] = $count !== 0 ? current($value) : null;
        }

        $this->query->update($data);
    }

}
