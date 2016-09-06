<?php

namespace PHPixie\ORM\Conditions\Builder;

class Container extends \PHPixie\Database\Conditions\Builder\Container
                implements \PHPixie\ORM\Conditions\Builder
{
    protected $conditions;
    protected $relationshipMap;
    protected $modelNameStack = array();
    protected $currentModelName;
    
    public function __construct($conditions, $relationshipMap, $modelName, $defaultOperator = '=')
    {
        $this->conditions = $conditions;
        $this->relationshipMap = $relationshipMap;
        parent::__construct($defaultOperator);
        $this->pushModelNameToStack($modelName);
    }

    public function addOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        $relationship = null;
        if(is_string($field)) {
            if(($pos = strpos($field, '>')) !== false || ($pos = strrpos($field, '.')) !== false) {
                $relationship = substr($field, 0, $pos);
                $field        = substr($field, $pos + 1);
            }
        }

        $condition = $this->buildOperatorCondition($field, $operator, $values);
        $this->addToRelationship($logic, $negate, $condition, $relationship);
        return $this;
    }
    
    public function startRelatedToConditionGroup($relationship, $logic = 'and', $negate = false)
    {
        $path = explode('.', $relationship);
        $root = null;
        $current = null;
        
        $modelName = $this->currentModelName;
        
        foreach ($path as $key => $step) {
            $side = $this->relationshipMap->get($modelName, $step);
            $modelName = $side->relatedModelName();
            
            $group = $this->buildRelatedToGroupCondition($step);
            
            if ($key == 0) {
                $root = $group;
            }else{
                $current->add($group);
            }
            
            $current = $group;
        }
        
        $this->addCondition($logic, $negate, $root);
        $this->pushGroupToStack($current);
        $this->pushModelNameToStack($modelName);
        return $this;
    }
    

    public function addRelatedToCondition($logic, $negate, $relationship, $condition = null)
    {
        $this->startRelatedToConditionGroup($relationship, $logic, $negate);
        if($condition !== null) {
            if (!is_callable($condition)) {
                $this->addInCondition('and', false, $condition);
            }else {
                call_user_func($condition, $this);
            }
        }
        
        return $this->endGroup();
    }

    public function addInCondition($logic, $negate, $items)
    {
        $condition = $this->buildInCondition($items);
        return $this->addCondition($logic, $negate, $condition);
    }

    protected function addToRelationship($logic, $negate, $condition, $relationship)
    {
        if ($relationship !== null) {
            $this->startRelatedToConditionGroup($relationship, $logic, $negate);
            $this->addCondition('and', false, $condition);
            $this->endGroup();
        }else{
            $this->addCondition($logic, $negate, $condition);
        }
    }
    
    protected function pushModelNameToStack($modelName)
    {
        $this->modelNameStack[] = $modelName;
        $this->currentModelName = $modelName;
    }
    
    public function endGroup()
    {
        $oldGroup = $this->currentGroup;
        parent::endGroup();
        
        if($oldGroup instanceof \PHPixie\ORM\Conditions\Condition\Collection\RelatedTo) {
            array_pop($this->modelNameStack);
            $this->currentModelName = end($this->modelNameStack);
        }
        return $this;
    }
    
    
    protected function buildGroupCondition()
    {
        return $this->conditions->group();
    }
    
    protected function buildOperatorCondition($field, $operator, $values)
    {
        return $this->conditions->operator($field, $operator, $values);
    }
    
    protected function buildPlaceholderCondition($allowEmpty)
    {
        return $this->conditions->placeholder(
            $this->currentModelName,
            $this->defaultOperator,
            $allowEmpty
        );
    }
    
    protected function buildRelatedToGroupCondition($relationshipName)
    {
        return $this->conditions->relatedToGroup($relationshipName);
    }
    
    protected function buildInCondition($items)
    {
        return $this->conditions->in($this->currentModelName, $items);
    }
    
    public function addWhereOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        return $this->addOperatorCondition($logic, $negate, $field, $operator, $values);
    }

    public function startWhereConditionGroup($logic = 'and', $negate = false)
    {
        return $this->startConditionGroup($logic, $negate);
    }

    public function addWherePlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        return $this->addPlaceholder($logic, $negate, $allowEmpty);
    }
    
    public function addWhereCondition($logic, $negate, $condition)
    {
        return $this->addCondition($logic, $negate, $condition);
    }
    
    public function buildWhereCondition($logic, $negate, $args)
    {
        return $this->buildCondition($logic, $negate, $args);
    }

    public function where()
    {
        return $this->buildCondition('and', false, func_get_args());
    }

    public function andWhere()
    {
        return $this->buildCondition('and', false, func_get_args());
    }

    public function orWhere()
    {
        return $this->buildCondition('or', false, func_get_args());
    }

    public function xorWhere()
    {
        return $this->buildCondition('xor', false, func_get_args());
    }

    public function whereNot()
    {
        return $this->buildCondition('and', true, func_get_args());
    }

    public function andWhereNot()
    {
        return $this->buildCondition('and', true, func_get_args());
    }

    public function orWhereNot()
    {
        return $this->buildCondition('or', true, func_get_args());
    }

    public function xorWhereNot()
    {
        return $this->buildCondition('xor', true, func_get_args());
    }

    public function startWhereGroup()
    {
        return $this->startConditionGroup('and', false);
    }

    public function startAndWhereGroup()
    {
        return $this->startConditionGroup('and', false);
    }

    public function startOrWhereGroup()
    {
        return $this->startConditionGroup('or', false);
    }

    public function startXorWhereGroup()
    {
        return $this->startConditionGroup('xor', false);
    }

    public function startWhereNotGroup()
    {
        return $this->startConditionGroup('and', true);
    }

    public function startAndWhereNotGroup()
    {
        return $this->startConditionGroup('and', true);
    }

    public function startOrWhereNotGroup()
    {
        return $this->startConditionGroup('or', true);
    }

    public function startXorWhereNotGroup()
    {
        return $this->startConditionGroup('xor', true);
    }

    public function endWhereGroup()
    {
        return $this->endGroup();
    }
    
    public function in($items)
    {
        return $this->addInCondition('and', false, $items);
    }
    
    public function andIn($items)
    {
        return $this->addInCondition('and', false, $items);
    }

    public function orIn($items)
    {
        return $this->addInCondition('or', false, $items);
    }

    public function xorIn($items)
    {
        return $this->addInCondition('xor', false, $items);
    }
    
    public function notIn($items)
    {
        return $this->addInCondition('and', true, $items);
    }
    
    public function andNotIn($items)
    {
        return $this->addInCondition('and', true, $items);
    }

    public function orNotIn($items)
    {
        return $this->addInCondition('or', true, $items);
    }

    public function xorNotIn($items)
    {
        return $this->addInCondition('xor', true, $items);
    }
    
    
    public function relatedTo($relationship, $items = null)
    {
        return $this->addRelatedToCondition('and', false, $relationship, $items);
    }
    
    public function andRelatedTo($relationship, $items = null)
    {
        return $this->addRelatedToCondition('and', false, $relationship, $items);
    }
    
    public function orRelatedTo($relationship, $items = null)
    {
        return $this->addRelatedToCondition('or', false, $relationship, $items);
    }
    
    public function xorRelatedTo($relationship, $items = null)
    {
        return $this->addRelatedToCondition('xor', false, $relationship, $items);
    }
    
    public function notRelatedTo($relationship, $items = null)
    {
        return $this->addRelatedToCondition('and', true, $relationship, $items);
    }
    
    public function andNotRelatedTo($relationship, $items = null)
    {
        return $this->addRelatedToCondition('and', true, $relationship, $items);
    }
    
    public function orNotRelatedTo($relationship, $items = null)
    {
        return $this->addRelatedToCondition('or', true, $relationship, $items);
    }
    
    public function xorNotrelatedTo($relationship, $items = null)
    {
        return $this->addRelatedToCondition('xor', true, $relationship, $items);
    }
    

    public function startRelatedToGroup($relationship)
    {
        return $this->startRelatedToConditionGroup($relationship, 'and', false);
    }
    
    public function startAndRelatedToGroup($relationship)
    {
        return $this->startRelatedToConditionGroup($relationship, 'and', false);
    }
    
    public function startOrRelatedToGroup($relationship)
    {
        return $this->startRelatedToConditionGroup($relationship, 'or', false);
    }
    
    public function startXorRelatedToGroup($relationship)
    {
        return $this->startRelatedToConditionGroup($relationship, 'xor', false);
    }
    
    public function startNotRelatedToGroup($relationship)
    {
        return $this->startRelatedToConditionGroup($relationship, 'and', true);
    }
    
    public function startAndNotRelatedToGroup($relationship)
    {
        return $this->startRelatedToConditionGroup($relationship, 'and', true);
    }
    
    public function startOrNotRelatedToGroup($relationship)
    {
        return $this->startRelatedToConditionGroup($relationship, 'or', true);
    }
    
    public function startXorNotRelatedToGroup($relationship)
    {
        return $this->startRelatedToConditionGroup($relationship, 'xor', true);
    }
    
}