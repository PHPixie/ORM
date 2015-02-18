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
        
        $inGroup = $this->conditions->group();
        $this->copyLogicAndNegated($inCondition, $inGroup);
        
        $ids = array();
        foreach($items as $item) {
            
            if($item instanceof \PHPixie\ORM\Models\Type\Database\Query) {
                $queryGroup = $this->conditions->group();
                $this->setLogicAndNegated($queryGroup, 'or', false);
                $queryGroup->setConditions($item->getConditions());
                $inGroup->add($queryGroup);            
            }else{
                $ids[]=$item->id();
            }
        }
        
        if(!empty($ids) || empty($items)) {
            $idField = $this->models->database()->config($modelName)->idField;
            $operatorCondition = $this->conditions->operator($idField, 'in', array($ids));
            $this->setLogicAndNegated($operatorCondition, 'or', false);
            $inGroup->add($operatorCondition);    
        }
        
        return $inGroup;
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