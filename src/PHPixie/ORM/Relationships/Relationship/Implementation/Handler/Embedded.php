<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation\Handler;

abstract class Embedded extends \PHPixie\ORM\Relationships\Relationship\Implementation\Handler
{
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

}