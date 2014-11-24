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
    
    protected function mapOperatorCondition($builder, $condition, $embeddedPath = null)
    {
        $prefix = $embeddedPath === null ? '' : $embeddedPath.'.';
        $builder->addOperatorCondition(
            $condition->logic(),
            $condition->negated(),
            $prefix.$condition->field,
            $condition->operator,
            $condition->values
        );
    }
    
    protected function mapConditionGroup($builder, $modelName, $group, $plan, $embeddedPath = null)
    {
        $builder->startGroup($group->logic(), $group->negated());
        $this->mapConditions($builder, $modelName, $group->conditions(), $plan, $embeddedPath);
        $builder->endGroup();
    }
    
    protected function mapRelationshipGroup($builder, $modelName, $group, $plan, $embeddedPath = null)
    {
        $side = $this->relationshipMap->getSide($modelName, $group->relationship());
        $type = $side->relationshipType();
        $handler = $this->relationships->get($type)->handler();
        
        if($embeddedPath !== null) {
            $handler->mapSubdocument($builder, $side, $group, $plan, $embeddedPath);
        }else{
            $handler->mapQuery($builder, $side, $group, $plan);
        }
    }
    
    protected function mapCollection($builder, $modelName, $collectionCondition, $plan, $embeddedPath = null)
    {
        if($embeddedPath !== null)
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
            $collectionCondition->negated()
        );
    }
    
    

    protected function mapConditions($builder, $modelName, $conditions, $plan, $embeddedPath = null)
    {
        foreach ($conditions as $condition) {
            
            if ($condition instanceof \PHPixie\ORM\Conditions\Condition\Operator) {
                $this->mapOperatorCondition($builder, $condition, $embeddedPath);
                
            }elseif ($condition instanceof \PHPixie\ORM\Conditions\Condition\Collection) {
                $this->mapCollection($builder, $modelName, $condition, $plan, $embeddedPath);

            }elseif ($condition instanceof \PHPixie\ORM\Conditions\Condition\Group\Relationship) {
                $this->mapRelationshipGroup($builder, $modelName, $condition, $plan, $embeddedPath);

            }elseif ($condition instanceof \PHPixie\ORM\Conditions\Condition\Group) {
                $this->mapConditionGroup($builder, $modelName, $condition, $plan, $embeddedPath);

            }else {
                throw new \PHPixie\ORM\Exception\Mapper("Unexpected condition encountered");
            }
        }
    }
    
    public function mapDatabaseQuery($query, $modelName, $conditions, $plan)
    {
        return $this->mapConditions($query, $modelName, $conditions, $plan);
    }
    
    public function mapSubdocument($subdocument, $modelName, $conditions, $plan, $path)
    {
        return $this->mapConditions($subdocument, $modelName, $conditions, $plan, $path);
    }
    
}
