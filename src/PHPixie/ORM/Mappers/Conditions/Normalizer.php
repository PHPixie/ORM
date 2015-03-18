<?php

namespace PHPixie\ORM\Mappers\Conditions;

class Normalizer
{
    protected $conditions;
    protected $models;
    
    public function __construct($conditions, $models)
    {
        $this->conditions = $conditions;
        $this->models     = $models;
    }
    
    public function normalizeIn($inCondition)
    {
        $modelName = $inCondition->modelName();
        $items  = $inCondition->items();
        $idField = $this->models->database()->config($modelName)->idField;
        
        $inGroup = $this->conditions->group();
        $this->copyLogicAndNegated($inCondition, $inGroup);
        
        $ids = array();
        foreach($items as $item) {
            
            if(is_scalar($item)) {
                $ids[] = $item;
                continue;
            }
            
            if($item instanceof \PHPixie\ORM\Models\Type\Database\Query) {
                $condition = $this->getSubqueryCondition($item, $idField);
                $inGroup->add($condition);
                continue;
            }
            
            $ids[]=$item->id();
        }
        
        if(!empty($ids)) {
            
            if(count($ids) === 1) {
                $operator = '=';
                $values   = array(current($ids));
            }else{
                $operator = 'in';
                $values   = array($ids);                
            }
            
            $operatorCondition = $this->conditions->operator($idField, $operator, $values);
            $this->setLogicAndNegated($operatorCondition, 'or', false);
            $inGroup->add($operatorCondition); 
        }
        
        if(empty($items)) {
            $operatorCondition = $this->conditions->operator($idField, '=', array(null));
            $inGroup->add($operatorCondition);
        }
        
        return $inGroup;
    }
    
    protected function getSubqueryCondition($query, $idField)
    {
        if($query->getLimit() !== null || $query->getOffset() !== null) {
            $condition = $this->conditions->subquery($idField, $query, $idField);

        }else{
            $condition = $this->conditions->group();
            $condition->setConditions($query->getConditions());
        }

        $this->setLogicAndNegated($condition, 'or', false);
        return $condition;
    }
    
    protected function copyLogicAndNegated($source, $target)
    {
        $this->setLogicAndNegated(
            $target,
            $source->logic(),
            $source->isNegated()
        );
    }
    
    protected function setLogicAndNegated($condition, $logic, $isNegated)
    {
        $condition->setLogic($logic);
        $condition->setIsNegated($isNegated);
    }
}