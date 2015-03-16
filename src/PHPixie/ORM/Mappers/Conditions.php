<?php

namespace PHPixie\ORM\Mappers;

class Conditions
{
    protected $mappers;
    protected $planners;
    protected $relationships;
    protected $relationshipMap;
    
    public function __construct($mappers, $planners, $relationships, $relationshipMap)
    {
        $this->mappers         = $mappers;
        $this->planners        = $planners;
        $this->relationships   = $relationships;
        $this->relationshipMap = $relationshipMap;
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
    
    protected function mapConditionCollection($builder, $modelName, $collection, $plan)
    {
        $builder->startConditionGroup($collection->logic(), $collection->isNegated());
        $this->mapConditions($builder, $modelName, $collection->conditions(), $plan);
        $builder->endGroup();
    }
    
    protected function mapRelatedToCollection($builder, $modelName, $collection, $plan)
    {
        $side = $this->relationshipMap->get($modelName, $collection->relationship());
        $type = $side->relationshipType();
        $handler = $this->relationships->get($type)->handler();
        
        if($builder instanceof \PHPixie\Database\Query) {
            $handler->mapDatabaseQuery($builder, $side, $collection, $plan);
        }else{
            $handler->mapEmbeddedContainer($builder, $side, $collection, $plan);
        }
    }
    
    protected function mapInCondition($builder, $modelName, $inCondition, $plan)
    {
        $collectionCondition = $this->mappers->conditionsNormalizer()->normalizeIn($inCondition);
        $this->mapConditionCollection($builder, $modelName, $collectionCondition, $plan);
    }
    
    protected function mapSubqueryCondition($builder, $modelName, $condition, $plan)
    {
        $this->planners->in()->databaseModelQuery(
            $builder,
            $condition->field(),
            $condition->subquery(),
            $condition->subqueryField(),
            $plan,
            $condition->logic(),
            $condition->isNegated()
        );
    }
    
    protected function mapConditions($builder, $modelName, $conditions, $plan)
    {
        foreach ($conditions as $condition) {
   
            if ($condition instanceof \PHPixie\ORM\Conditions\Condition\Field\Operator) {
                $this->mapOperatorCondition($builder, $condition);
                
            }elseif ($condition instanceof \PHPixie\ORM\Conditions\Condition\In) {
                $this->mapInCondition($builder, $modelName, $condition, $plan);

            }elseif ($condition instanceof \PHPixie\ORM\Conditions\Condition\Collection\RelatedTo) {
                $this->mapRelatedToCollection($builder, $modelName, $condition, $plan);

            }elseif ($condition instanceof \PHPixie\ORM\Conditions\Condition\Collection) {
                $this->mapConditionCollection($builder, $modelName, $condition, $plan);

            }elseif ($condition instanceof \PHPixie\ORM\Conditions\Condition\Field\Subquery) {
                $this->mapSubqueryCondition($builder, $modelName, $condition, $plan);

            }else {
                throw new \PHPixie\ORM\Exception\Mapper("Unexpected condition encountered");
            }
        }
    }
    
    public function map($builder, $modelName, $conditions, $plan)
    {
        $conditions = $this->mappers->conditionsOptimizer()->optimize($conditions);
        $this->mapConditions($builder, $modelName, $conditions, $plan);
    }
}
