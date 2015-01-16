<?php

namespace PHPixie\ORM\Mappers\Conditions;

class In
{
    public function map($builder, $modelName, $inCondition, $plan)
    {
        $config = $this->models->config($modelName);
        if($config->type() !== 'database') 
            
        $this->mapDatabase($builder, $config, $inCondition, $plan);
    }
    
    protected function mapEmbedded($query, $config, $inCondition, $plan)
    {
    
    }
    
    protected function mapDatabase($query, $config, $inCondition, $plan)
    {
        $logic  = $inCondition->logic();
        $negate = $inCondition->isNegated();
        $items  = $inCondition->items();
        
        if($items === null) {
            $this->addInAllCondition($query, $config, $logic, $negate);
            
        }elseif($items === array()) {
            $this->addInAllCondition($query, $config, $logic, !$negate);
            
        }else{
            
            $builder->startConditionGroup($inCondition->logic(), $inCondition->isNegated());
            $ids = array();
            
            if($item instanceof \PHPixie\ORM\Models\Type\Database\Query) {
                $this->mapInDatabaseQuery($builder, $modelName, $item, $plan);
                
            }else{
                $ids[]=$item->id();
                
            }
            
            if(!empty($ids)) {
                $builder->addInOperatorCondition($config->idField(), $ids, 'or', false);
            }
            
            $query->endGroup();
        }
    }
    
    protected function mapInDatabaseQuery($builder, $modelName, $query, $plan)
    {
        $builder->startGroup('or', false);
        $this->mapConditions($builder, $modelName, $query->conditions(), $plan);
        $builder->endGroup();
    }
    
    protected function addInAllCondition($query, $config, $logic, $negate)
    {
        $builder->addInOperatorCondition($config->idField(), array(), $logic, !$negate);
    }
    
}