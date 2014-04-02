<?php

namespace PHPixie\ORM\Model;

abstract class Preloader
{
    protected $relationshipType;
    protected $side;
    protected $repository;
    protected $resultStep;
    protected $items = array();

    public function __construct($relationshipType, $side, $repository, $resultStep)
    {
        $this->relationshipType = $relationshipType;
        $this->side = $side;
        $this->repository = $repository;
        $this->resultStep = $resultStep;
    }

    public function getModel($id)
    {
        
        $data = $this->items[$id];
        if($data instanceof \PHPixie\ORM\Model)
            return $data;

        $model = $this->loader->load($data);
        $this->items[$id] = $model;

        return $model;
    }
	
	public function resultStep()
	{
		return $this->resultStep;
	}
    
}
