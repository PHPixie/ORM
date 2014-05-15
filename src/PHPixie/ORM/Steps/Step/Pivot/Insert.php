<?php

namespace PHPixie\ORM\Steps\Step\Pivot;

class Insert extends \PHPixie\ORM\Steps\Step\Insert\Batch
{
    protected $cartesianStep;
    
    public function __construct($insertQuery, $queryPlanner, $fields, $cartesianStep)
    {
        parent::_construct($insertQuery, $queryPlanner);
        $this->fields = $fields;
        $this->cartesianStep = $cartesianStep;
    }
    
    protected function prepareBatchData()
    {
        $this->data = $this->cartesianStep->product();
    }
}
    
    
    
    protected $fields;
    
    protected $
    protected $insertQuery;
    protected $selectQuery;
    protected $fields;
    protected $cartesianStep;
    
    public function execute()
    {
        $product = $this->cartesianStep->product();
        if($this->skipDuplicates){
        $query = $this->query('select')->fields($this->fields);
        foreach($this->fields as $key => $field)
            $query->where($field, 'in', $product[$key]);
    }

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
