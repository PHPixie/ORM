<?php

namespace PHPixie\ORM\Relationships;

class Map
{
    protected $propertyMap = array();

    public function __construct($orm, $config)
    {
        foreach ($config->data() as $key => $params) {
            $type = $params['type'];
            $relationshipConfig = $this->config->slice($key);
            $relationship = $orm->relationshipType($type);
            $sides = $relationship->getSides($relationshipConfig);
            foreach($sides as $side)
                $this->addSide($side);
        }
    }

    public function addSide($side)
    {
        $modelName = $side->modelName();
        $propertyName = $side->propertyName();

        if (!isset($this->propertyMap[$modelName]))
            $this->propertyMap[$modelName] = array();

        if (isset($this->propertyMap[$modelName][$propertyName]))
            throw new \PHPixie\ORM\Exception\Mapper("Property '$propertyName' on '$modelName' model has already been defined by a different relationship.");

        $this->propertyMap[$model][$property] = $side;
    }

    public function getSide($modelName, $propertyName)
    {
        return $this->propertyMap[$modelName][$propertyName];
    }

    public function modelProperty($model, $propertyName)
    {
        $side = $this->getSide($model->modelName(), $propertyName);
        $relationshipType = $this->orm->relationshipType($side->relationshipType());

        return $relationshipType->modelProperty($side, $model);
    }

    public function queryProperty($query, $name)
    {
        $side = $this->getSide($query->modelName(), $propertyName);
        $relationshipType = $this->orm->relationshipType($side->relationshipType());

        return $relationshipType->queryProperty($side, $model);
    }

}
