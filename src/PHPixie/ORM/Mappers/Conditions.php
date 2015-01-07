<?php

namespace PHPixie\ORM\Mappers;

class Conditions
{
    protected $models;
    protected $relationships;
    protected $planners;
    
    protected $databaseModel;
    protected $relationshipMap;

    public function __construct($models, $relationships, $planners)
    {
        $this->models = $models;
        $this->relationships = $relationships;
        $this->planners = $planners;
        
        $this->databaseModel   = $models->database();
        $this->relationshipMap = $relationships->map();
    }
    
    protected function mapOperatorCondition($builder, $condition)
    {
        $builder->addOperatorCondition(
            $condition->logic(),
            $condition->isNegated(),
            $condition->field(),
            $condition->operator(),
            $condition->values()
        );
    }
    
    protected function mapConditionGroup($builder, $modelName, $group, $plan)
    {
        $builder->startGroup($group->logic(), $group->isNegated());
        $this->mapConditions($builder, $modelName, $group->conditions(), $plan);
        $builder->endGroup();
    }
    
    public function mapEmbeddedCollection($builder, $modelName, $embeddedCollection, $plan)
    {
        
    }
    
    protected function mapRelationshipGroup($builder, $modelName, $group, $plan)
    {
        $side = $this->relationshipMap->getSide($modelName, $group->relationship());
        $type = $side->relationshipType();
        $handler = $this->relationships->get($type)->handler();
        
        if($builder instanceof \PHPixie\Database\Query) {
            $handler->mapDatabaseQuery($builder, $side, $group, $plan);
        }else{
            $handler->mapEmbeddedContainer($builder, $side, $group, $plan);
        }
    }
    
    protected function mapInCondition($builder, $modelName, $collectionCondition, $plan)
    {
        if(!($builder instanceof \PHPixie\Database\Query))
            throw new \PHPixie\ORM\Exception\Mapper("Collection conditions are not allowed for embedded models");
        
        $collection = $this->planners->collection($modelName, $collectionCondition->items());
        $idField = $this->databaseModel->config($modelName)->idField;
        
        $this->planners->in()->collection(
            $builder,
            $idField,
            $collection,
            $idField,
            $plan,
            $collectionCondition->logic(),
            $collectionCondition->isNegated()
        );
    }
    
    public function map($builder, $modelName, $conditions, $plan)
    {
        foreach ($conditions as $condition) {
            
            if ($condition instanceof \PHPixie\ORM\Conditions\Condition\Operator) {
                $this->mapOperatorCondition($builder, $condition);
                
            }elseif ($condition instanceof \PHPixie\ORM\Conditions\Condition\In) {
                $this->mapInCondition($builder, $modelName, $condition, $plan);

            }elseif ($condition instanceof \PHPixie\ORM\Conditions\Condition\Group\Relationship) {
                $this->mapRelationshipGroup($builder, $modelName, $condition, $plan);

            }elseif ($condition instanceof \PHPixie\ORM\Conditions\Condition\Group) {
                $this->mapConditionGroup($builder, $modelName, $condition, $plan);

            }else {
                throw new \PHPixie\ORM\Exception\Mapper("Unexpected condition encountered");
            }
        }
    }
}
