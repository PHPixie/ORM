<?php

namespace PHPixie\ORM\Relationships\Relationship\Handler;

abstract class Handler
{
    protected $ormBuilder;
    protected $repositories;
    protected $planners;
    protected $plans;
    protected $steps;
    protected $loaders;
    protected $relationship;
    protected $groupMapper;
    protected $cascadeMapper;

    public function __construct($ormBuilder, $repositories, $planners, $plans, $steps,
                                $loaders, $relationship, $groupMapper, $cascadeMapper)
    {
        $this->ormBuilder         = $ormBuilder;
        $this->repositories       = $repositories;
        $this->planners           = $planners;
        $this->plans              = $plans;
        $this->steps              = $steps;
        $this->loaders            = $loaders;
        $this->relationship       = $relationship;
        $this->groupMapper        = $groupMapper;
        $this->cascadeMapper      = $cascadeMapper;
    }

    protected function getLoadedProperty($model, $propertyName)
    {
        if ($model === null)
            return null;

        $property = $model->relationshipProperty($propertyName, false);
        if ($property === null || !$property->isLoaded())
            return null;

        return $property;
    }

    protected function assertModelName($model, $requiredModel)
    {
        if ($model->modelName() !== $requiredModel)
            throw new \PHPixie\ORM\Exception\Relationship("Only '$requiredModel' models can be used for this relationship.");
    }

    protected function deletePlanResultStep($plan)
    {
        if (($resultStep = $plan->resultStep()) !== null)
            return $resultStep;

        $resultStep = $this->steps->result(null);
        $plan->setResultStep($resultStep);

        return $resultStep;
    }

    protected function getPropertyIfLoaded($model, $propertyName)
    {
        $property = $model->relationshipProperty($propertyName);
        if ($property === null || !$property->isLoaded())
            return null;
        return $property;
    }


    public function handleDeletion($modelName, $side, $resultStep, $plan)
    {

    }

    abstract public function mapPreload();
    abstract public function mapQuery();
}
