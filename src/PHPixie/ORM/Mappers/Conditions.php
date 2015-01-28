<?php

namespace PHPixie\ORM\Mappers;

class Conditions
{
    protected $mappers;
    protected $models;
    protected $relationships;
    
    public function __construct($mappers, $models, $maps, $relationships)
    {
        $this->mappers = $models;
        $this->models = $models;
        $this->maps = $maps;
        $this->relationships = $relationships;
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
    
    protected function mapRelationshipGroup($builder, $modelName, $group, $plan)
    {
        $side = $this->maps->query()->get($modelName, $group->relationship());
        $type = $side->relationshipType();
        $handler = $this->relationships->get($type)->handler();
        
        if($builder instanceof \PHPixie\Database\Query) {
            $handler->mapDatabaseQuery($builder, $side, $group, $plan);
        }else{
            $handler->mapEmbeddedContainer($builder, $side, $group, $plan);
        }
    }
    
    protected function mapDatabaseQuery($builder, $modelName, $query, $plan)
    {
        $builder->startGroup('or', false);
        $this->mapConditions($builder, $modelName, $query->conditions(), $plan);
        $builder->endGroup();
    }

    protected function mapInCondition($builder, $modelName, $inCondition, $plan)
    {
        if(!($builder instanceof \PHPixie\Database\Query))
            throw new \PHPixie\ORM\Exception\Mapper("Collection conditions are not allowed for embedded models");
        
        $items  = $inCondition->items();
        
        $builder->startConditionGroup($inCondition->logic(), $inCondition->isNegated());
        
        $ids = array();
        foreach($items as $item) {
            if($item instanceof \PHPixie\ORM\Models\Type\Database\Query) {
                $builder->startGroup('or', false);
                $this->mapConditions($builder, $modelName, $query->conditions(), $plan);
                $builder->endGroup();

            }else{
                $ids[]=$item;

            }
        }
        
        if(!empty($ids) || empty($items)) {
            $idField = $this->models->database()->config($modelName)->idField;
            $builder->addInOperatorCondition($idField, $ids, 'or', false);
        }
        
        $this->endGroup();
    }
    
    public function map($builder, $modelName, $conditions, $plan)
    {
        $conditions = $this->mappers->conditionsOptimizer()->optimize($conditions);
        
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
