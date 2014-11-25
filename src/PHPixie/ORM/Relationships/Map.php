<?php

namespace PHPixie\ORM\Relationships;

class Map
{
    protected $ormBuilder;
    protected $propertyMap = array();

    public function __construct($ormBuilder, $config)
    {
        $this->ormBuilder = $ormBuilder;
        $this->addSidesFromConfig($config);
    }

    public function addSidesFromConfig($config)
    {
        foreach ($config->data() as $key => $params) {
            $type = $params['type'];
            $relationshipConfig = $config->slice($key);
            $relationship = $this->ormBuilder->relationship($type);
            $sides = $relationship->getSides($relationshipConfig);
            foreach($sides as $side)
                $this->addSide($side);
        }
    }
    
    public function modelSides()
    {
        return array_values($this->propertyMap[$modelName]);
    }

    public function addSide($side)
    {
        $modelName = $side->modelName();
        $propertyName = $side->propertyName();

        if (!isset($this->propertyMap[$modelName]))
            $this->propertyMap[$modelName] = array();

        if (isset($this->propertyMap[$modelName][$propertyName]))
            throw new \PHPixie\ORM\Exception\Mapper("Property '$propertyName' on '$modelName' model has already been defined by a different relationship.");

        $this->propertyMap[$modelName][$propertyName] = $side;
    }

    public function getSide($modelName, $propertyName)
    {
        return $this->propertyMap[$modelName][$propertyName];
    }

    public function entityProperty($model, $propertyName)
    {
        $side = $this->getSide($model->modelName(), $propertyName);
        $relationship = $this->ormBuilder->relationship($side->relationshipType());

        return $relationship->modelProperty($side, $model);
    }

    public function queryProperty($model, $propertyName)
    {
        $side = $this->getSide($model->modelName(), $propertyName);
        $relationship = $this->ormBuilder->relationship($side->relationshipType());

        return $relationship->queryProperty($side, $model);
    }
    
    public function entityPropertyNames($name){
    
    }

}
