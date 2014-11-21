<?php

namespace PHPixie\ORM\Mapper;

class Group
{
    protected $repositories;
    protected $relationships;
    protected $planners;

    public function __construct($repositories, $relationships, $planners)
    {
        $this->repositories = $repositoryRegistry;
        $this->relationship = $relationships;
        $this->planners = $planners;
    }
    
    protected function mapOperatorCondition($builder, $condition, $fieldPrefix = null)
    {
        $prefix = $fieldPrefix === null ? '' : $fieldPrefix.'.';
        
        $builder->addOperatorCondition(
            $condition->logic(),
            $condition->negated(),
            $prefix.$condition->field,
            $condition->operator,
            $condition->values
        );
    }
    
    protected function mapConditionGroup($group, $builder, $modelName, $plan, $fieldPrefix = null)
    {
        $query->startWhereGroup($group->logic, $group->negated());
        $this->mapConditions($query, $group->conditions(), $modelName, $plan, $fieldPrefix);
        $builder->endWhereGroup();
    }
    
    protected function mapRelationshipGroup($group, $query, $modelName, $plan, $embeddedPrefix = null)
    {
        $side = $this->relationshipMap->getSide($modelName, $group->relationship);
        $type = $side->relationshipType();
        $handler = $this->relationships($type)->handler();
        
        if($embeddedPrefix !== null) {
            $handler->mapSubdocument($side, $query, $group, $plan, $embeddedPrefix);
        }else{
            $handler->mapQuery($side, $query, $group, $plan);
        }
    }
    
    protected function mapCollection($builder, $modelName, $collection, $embeddedPrefix)
    {
        if($embeddedPrefix !== null)
            throw
            
        $idField = $this->repositories->get($modelName)->config()->idField;
        $this->planners->in()->collection(
            $builder,
            $idField,
            $collectionCondition->collection(),
            $idField,
            $plan,
            $collectionCondition->logic,
            $collectionCondition->negated()
        );
    }
    
    

    protected function mapConditions($builder, $conditions, $modelName, $plan, $embeddedPrefix = null)
    {
        foreach ($conditions as $cond) {

            if ($cond instanceof \PHPixie\ORM\Conditions\Condition\Operator) {
                $builder->mapOperatorCondition($builder, $condition, $embeddedPrefix);

            }elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\Collection) {
                $this->mapCollection($builder, $condition, $embeddedPrefix);

            }elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\Group\Relationship) {
                $this->mapRelationshipGroup($group, $currentModel, $query, $plan, $embeddedPrefix);

            }elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\Group) {
                $this->mapConditionGroup($cond, $query, $currentModel, $plan, $embeddedPrefix);

            }else
                throw new \PHPixie\ORM\Exception\Mapper("Unexpected condition encountered");
        }
    }
    
    public function mapDatabaseQuery($query, $conditions, $modelName, $plan)
    {
        return $this->mapConditions($builder, $conditions, $modelName, $plan);
    }
    
    public function mapSubdocument($query, $conditions, $modelName, $plan, $prefix)
    {
        return $this->mapConditions($builder, $conditions, $modelName, $plan, $prefix);
    }
    
}
