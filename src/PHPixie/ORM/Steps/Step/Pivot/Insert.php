<?php

namespace PHPixie\ORM\Steps\Step\Pivot;

class Insert extends \PHPixie\ORM\Steps\Step
{
    protected $queryPlanner;
    protected $queryConnection;
    protected $queryTarget;
    protected $fields;
    protected $cartesianStep;

    public function __construct($queryPlanner, $queryConnection, $queryTarget, $fields, $cartesianStep)
    {
        $this->queryPlanner       = $queryPlanner;
        $this->queryConnection    = $queryConnection;
        $this->queryTarget        = $queryTarget;
        $this->fields             = $fields;
        $this->cartesianStep      = $cartesianStep;
    }

    public function execute()
    {
        $product = $this->cartesianStep->product();

        $query = $this->query('select')->fields($this->fields);
        foreach($this->fields as $key => $field)
            $query->where($field, 'in', $product[$key]);

        $existing = array();
        foreach($query->execute() as $existingItem)
            $existing[] = array_values(get_object_vars($existingItem));

        $filteredProduct = array();
        foreach($product as $productRow)
            if(!in_array($productRow, $existing))
                $filteredProduct[] = $productRow;

        $insertQuery = $this->query('insert');
        $this->queryPlanner->setBatchData($insertQuery, $this->fields, $filteredProduct);
        $insertQuery->execute();
    }

    protected function query($type)
    {
        $query = $this->queryConnection->query($type);
        $this->queryPlanner->setSource($query, $this->queryTarget);

        return $query;
    }

    public function usedConnections()
    {
        return array($this->queryConnection);
    }
}
