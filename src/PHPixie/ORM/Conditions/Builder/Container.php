<?php

namespace PHPixie\ORM\Conditions\Builder;

class Container extends \PHPixie\Database\Conditions\Builder\Container
                implements \PHPixie\ORM\Conditions\Builder
{
    public function __construct($conditions, $defaultOperator = '=')
    {
        parent::__construct($conditions, $defaultOperator);
    }

    public function addOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        $relationship = null;
        
        if (($pos = strpos($field, '>')) !== false || ($pos = strrpos($field, '.')) !== false) {
            $relationship = substr($field, 0, $pos);
            $field = substr($field, $pos + 1);
        }

        $condition = $this->conditions->operator($field, $operator, $values);
        $this->addToRelationship($logic, $negate, $condition, $relationship);
    }
    
    public function startRelatedToConditionGroup($relationship, $logic = 'and', $negate = false)
    {
        $path = explode('.', $relationship);
        $root = null;
        $current = null;
        
        foreach ($path as $key => $step) {
            $group = $this->conditions->relationshipGroup($step);
            
            if ($key == 0) {
                $root = $group;
            }else{
                $current->add($group);
            }
            
            $current = $group;
        }
        
        $this->addToCurrentGroup($logic, $negate, $root);
        $this->pushGroup($current);
        return $this;
    }
    

    public function addRelatedToCondition($logic, $negate, $relationship, $condition)
    {
        if (!is_callable($condition))
            return $this->addInCondition($logic, $negate, $condition, $relationship);

        $this->startRelatedToConditionGroup($relationship, $logic, $negate);
        call_user_func($condition, $this);
        $this->endGroup();
        
        return $this;
    }

    public function addInCondition($logic, $negate, $items, $relationship = null)
    {
        $condition = $this->conditions->collection($items);
        $this->addToRelationship($logic, $negate, $condition, $relationship);
        return $this;
    }

    protected function addToRelationship($logic, $negate, $condition, $relationship)
    {
        if ($relationship !== null) {
            $this->startRelatedToConditionGroup($relationship, $logic, $negate);
            $this->addToCurrentGroup('and', false, $condition);
            $this->endGroup();
        }else{
            $this->addToCurrentGroup($logic, $negate, $condition);
        }
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

    public function where()
    {
        return $this->addCondition('and', false, func_get_args());
    }

    public function andWhere()
    {
        return $this->addCondition('and', false, func_get_args());
    }

    public function orWhere()
    {
        return $this->addCondition('or', false, func_get_args());
    }

    public function xorWhere()
    {
        return $this->addCondition('xor', false, func_get_args());
    }

    public function whereNot()
    {
        return $this->addCondition('and', true, func_get_args());
    }

    public function andWhereNot()
    {
        return $this->addCondition('and', true, func_get_args());
    }

    public function orWhereNot()
    {
        return $this->addCondition('or', true, func_get_args());
    }

    public function xorWhereNot()
    {
        return $this->addCondition('xor', true, func_get_args());
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
    
    
    public function relatedTo($relationship, $items)
    {
        return $this->addRelatedToCondition('and', false, $relationship, $items);
    }
    
    public function andRelatedTo($relationship, $items)
    {
        return $this->addRelatedToCondition('and', false, $relationship, $items);
    }
    
    public function orRelatedTo($relationship, $items)
    {
        return $this->addRelatedToCondition('or', false, $relationship, $items);
    }
    
    public function xorRelatedTo($relationship, $items)
    {
        return $this->addRelatedToCondition('xor', false, $relationship, $items);
    }
    
    public function notRelatedTo($relationship, $items)
    {
        return $this->addRelatedToCondition('and', true, $relationship, $items);
    }
    
    public function andNotRelatedTo($relationship, $items)
    {
        return $this->addRelatedToCondition('and', true, $relationship, $items);
    }
    
    public function orNotRelatedTo($relationship, $items)
    {
        return $this->addRelatedToCondition('or', true, $relationship, $items);
    }
    
    public function xorNotrelatedTo($relationship, $items)
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
    
    public function endRelatedToGroup()
    {
        return $this->endGroup();
    }
    
}
