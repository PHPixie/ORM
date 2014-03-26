<?php

namespace PHPixie\ORM\Properties;

class Builder
{
    protected $orm;
    protected $relationshipMap;

    public function __construct($orm, $relatonshipMap)
    {
        $this->relationshipMap = $relationshipMap;
    }

    public function modelProperty($model, $propertyName)
    {
        $side = $this->relationshipMap->getSide($model->modelName(), $propertyName);

        return $this->orm->modelProperty($side, $model);
    }

    public function queryProperty($model, $name)
    {
        $side = $this->relationshipMap->getSide($model->modelName(), $propertyName);

        return $this->orm->queryProperty($side, $model);
    }
}
