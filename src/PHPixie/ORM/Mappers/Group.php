<?php

namespace PHPixie\ORM\Mappers;

class Group
{
    protected $repositories;
    protected $relationships;
    protected $relationshipMap;
    protected $planners;

    public function __construct($repositories, $relationships, $planners)
    {
        $this->repositories = $repositories;
        $this->relationships = $relationships;
        $this->relationshipMap = $relationships->map();
        $this->planners = $planners;
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
        $idField = $this->repositories->get($modelName)->config()->idField;
        
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
