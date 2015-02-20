<?php

namespace PHPixie\ORM\Conditions\Builder;

class Proxy implements \PHPixie\ORM\Conditions\Builder
{
    protected $builder;
    protected $aliases = array(
        'and' => '_and',
        'or'  => '_or',
        'xor' => '_xor',
        'not' => '_not',
    );
    
    public function __construct($builder)
    {
        $this->builder = $builder;
    }
    
    public function __call($method, $params)
    {
        if(!array_key_exists($method, $this->aliases))
            throw new \PHPixie\ORM\Exception\Builder("Method $method does not exist.");

        return call_user_func_array(array($this, $this->aliases[$method]), $params);
    }
    
    public function addCondition($logic, $negate, $condition)
    {
        $this->builder->addCondition($logic, $negate, $condition);
        return $this;
    }
    
    public function buildCondition($logic, $negate, $args)
    {
        $this->builder->buildCondition($logic, $negate, $args);
        return $this;
    }
    
    public function addWhereCondition($logic, $negate, $condition)
    {
        $this->builder->addWhereCondition($logic, $negate, $condition);
        return $this;
    }
    
    public function buildWhereCondition($logic, $negate, $args)
    {
        $this->builder->buildWhereCondition($logic, $negate, $args);
        return $this;
    }

    public function addOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        $this->builder->addOperatorCondition($logic, $negate, $field, $operator, $values);
        return $this;
    }

    public function addInCondition($logic, $negate, $items, $relationship = null)
    {
        $this->builder->addInCondition($logic, $negate, $items, $relationship);
        return $this;
    }

    public function startConditionGroup($logic = 'and', $negate = false)
    {
        $this->builder->startConditionGroup($logic, $negate);
        return $this;
    }
    
    public function endGroup()
    {
        $this->builder->endGroup();
        return $this;
    }

    public function addPlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        $this->builder->addPlaceholder($logic, $negate, $allowEmpty);
        return $this;
    }

    public function addWhereOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        $this->builder->addWhereOperatorCondition($logic, $negate, $field, $operator, $values);
        return $this;
    }

    public function addWherePlaceholder($logic = 'and', $negate = false, $allowEmpty = true)
    {
        $this->builder->addWherePlaceholder($logic, $negate, $allowEmpty);
        return $this;
    }

    public function startWhereConditionGroup($logic = 'and', $negate = false)
    {
        $this->builder->startWhereConditionGroup($logic, $negate);
        return $this;
    }

    public function endWhereGroup()
    {
        $this->builder->endWhereGroup();
        return $this;
    }

    public function addRelatedToCondition($logic, $negate, $relationship, $items = null)
    {
        $this->builder->addRelatedToCondition($logic, $negate, $relationship, $items);
        return $this;
    }

    public function startRelatedToConditionGroup($relationship, $logic = 'and', $negate = false)
    {
        $this->builder->startRelatedToConditionGroup($relationship, $logic, $negate);
        return $this;
    }
    
//

    public function _and()
    {
        return $this->buildCondition('and', false, func_get_args());
    }

    public function _or()
    {
        return $this->buildCondition('or', false, func_get_args());
    }

    public function _xor()
    {
        return $this->buildCondition('xor', false, func_get_args());
    }

    public function _not()
    {
        return $this->buildCondition('and', true, func_get_args());
    }

    public function andNot()
    {
        return $this->buildCondition('and', true, func_get_args());
    }

    public function orNot()
    {
        return $this->buildCondition('or', true, func_get_args());
    }

    public function xorNot()
    {
        return $this->buildCondition('xor', true, func_get_args());
    }

    public function startGroup()
    {
        return $this->startConditionGroup('and', false);
    }

    public function startAndGroup()
    {
        return $this->startConditionGroup('and', false);
    }

    public function startOrGroup()
    {
        return $this->startConditionGroup('or', false);
    }

    public function startXorGroup()
    {
        return $this->startConditionGroup('xor', false);
    }

    public function startNotGroup()
    {
        return $this->startConditionGroup('and', true);
    }

    public function startAndNotGroup()
    {
        return $this->startConditionGroup('and', true);
    }

    public function startOrNotGroup()
    {
        return $this->startConditionGroup('or', true);
    }

    public function startXorNotGroup()
    {
        return $this->startConditionGroup('xor', true);
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
        return $this->startWhereConditionGroup('and', false);
    }

    public function startAndWhereGroup()
    {
        return $this->startWhereConditionGroup('and', false);
    }

    public function startOrWhereGroup()
    {
        return $this->startWhereConditionGroup('or', false);
    }

    public function startXorWhereGroup()
    {
        return $this->startWhereConditionGroup('xor', false);
    }

    public function startWhereNotGroup()
    {
        return $this->startWhereConditionGroup('and', true);
    }

    public function startAndWhereNotGroup()
    {
        return $this->startWhereConditionGroup('and', true);
    }

    public function startOrWhereNotGroup()
    {
        return $this->startWhereConditionGroup('or', true);
    }

    public function startXorWhereNotGroup()
    {
        return $this->startWhereConditionGroup('xor', true);
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