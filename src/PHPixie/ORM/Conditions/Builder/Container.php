<?php

namespace PHPixie\ORM\Conditions\Builder;

class Container extends \PHPixie\Database\Conditions\Builder\Container
{
    public function __construct($conditions, $defaultOperator = '=')
    {
        parent::__construct($conditions, $defaultOperator);
    }

    public function addOperatorCondition($logic, $negate, $field, $operator, $values)
    {
        $relationship = null;
        
        if (($pos = strpos($field, '>')) !== false || ($pos = strpos($field, '.', -1)) !== false) {
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
            $group = $this->relationshipGroup($rel);
            
            if ($key == 0) {
                $root = $group;
            }else{
                $current->add($group);
            }
            
            $current = $group;
        }

        $this->pushGroup($logic, $negate, $root);
        return $this;
    }
    

    public function addRelatedToCondition($logic, $negate, $relationship, $condition)
    {
        if (!is_callable($condition))
            return $this->addCollection($logic, $negate, $condition, $relationship);

        $this->startRelatedToConditionGroup($relationship, $logic, $negate);
        call_user_func($condition, $this);
        $this->endGroup();
        
        return $this;
    }

    protected function addInCondition($logic, $negate, $items, $relationship = null)
    {
        $condition = $this->conditions->collection($items);
        $this->addToRelationship($logic, $negate, $collection, $relationship);
    }

    protected function addToRelationship($logic, $negate, $condition, $relationship)
    {
        if ($relationship !== null)
            $this->startRelatedToConditionGroup($logic, $relationship);

        $this->addToCurrent($logic, $negate, $condition);

        if ($relationship !== null)
            $this->endGroup();
    }    
    
    public function where()
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

    public function in($collectionItems)
    {
        return $this->addCollection('and', false, $collectionItems);
    }

    public function orIn($collectionItems)
    {
        return $this->addCollection('or', false, $collectionItems);
    }

    public function xorIn($collectionItems)
    {
        return $this->addCollection('xor', false, $collectionItems);
    }

    public function andInNot($collectionItems)
    {
        return $this->addCollection('and', true, $collectionItems);
    }

    public function orInNot($collectionItems)
    {
        return $this->addCollection('or', true, $collectionItems);
    }

    public function xorInNot($collectionItems)
    {
        return $this->addCollection('xor', true, $collectionItems);
    }

    public function related($relationship, $condition)
    {
        return $this->addRelated('and', false, $relationship, $condition);
    }

    public function orRelated($relationship, $condition)
    {
        return $this->addRelated('or', false, $relationship, $condition);
    }

    public function xorRelated($relationship, $condition)
    {
        return $this->addRelated('xor', false, $relationship, $condition);
    }

    public function andRelatedNot($relationship, $condition)
    {
        return $this->addRelated('and', true, $relationship, $condition);
    }

    public function orRelatedNot($relationship, $condition)
    {
        return $this->addRelated('or', true, $relationship, $condition);
    }

    public function xorRelatedNot($relationship, $condition)
    {
        return $this->addRelated('xor', true, $relationship, $condition);
    }
}
