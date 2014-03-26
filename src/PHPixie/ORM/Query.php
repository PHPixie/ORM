<?php

namespace PHPixie\ORM;

class Query
{
    protected $conditionBuilder;
    protected $mapper;
    protected $limit;
    protected $offset;
    protected $orderBy = array();

    public function ->conditionBuilder($conditionBuilder, $mapper) {
        $this->conditionBuilder = $conditionBuilder;
        $this->mapper = $mapper;
    }

    public function limit($limit)
    {
        if (!is_numeric($limit))
            throw new \PHPixie\DB\Exception\Builder("Limit must be a number");

        $this->limit = $limit;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function offset($offset)
    {
        if (!is_numeric($offset))
            throw new \PHPixie\DB\Exception\Builder("Offset must be a number");

        $this->offset = $offset;

        return $this;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function orderBy($field, $dir = 'asc')
    {
        if ($dir !== 'asc' && $dir !== 'desc')
            throw new \PHPixie\DB\Exception\Builder("Order direction must be either 'asc' or  'desc'");

        $this->orderBy[] = array($field, $dir);

        return $this;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function planDelete($type)
    {
        return $this->mapper->mapDelete($this);
    }

    public function planUpdate($type, $data)
    {
        return $this->mapper->mapUpdate($this, $data);
    }

    public function planFind($type, $preload = array())
    {
        return $this->mapper->mapFind($this, $preload);
    }

    public function delete()
    {
        $this->deletePlan()->execute();
    }

    public function update($data)
    {
        $this->updatePlan($data)->execute();
    }

    public function find($preload = array())
    {
        return $this->planFind($this, $preload);
    }

    public function findOne($preload = array())
    {
        $oldLimit = $this->getLimit();
        $this->limit(1);
        $iterator = $this->findPlan($preload)->execute();
        $this->limit($oldLimit);

        return $iterator->current();
    }

    public function endConditionGroup($logic = 'and')
    {
        $this->conditionBuilder->->endGroup($logic);

        return $this;
    }

    public function addCondition($logic, $negate, $args)
    {
        $this->conditionBuilder->addCondition($logic, $negate, $args);

        return $this;
    }

    public function startGroup($logic='and', $negate = false)
    {
        $this->conditionBuilder->startGroup($logic, $negate);

        return $this;
    }

    public function endGroup()
    {
        $this->conditionBuilder->endGroup();

        return $this;
    }

    public function addCollection($logic, $negate, $collectionItems)
    {
        $this->conditionBuilder->addCondition($logic, $negate, $collectionItems);

        return $this;
    }

    public function addRelated($logic, $negate, $collectionItems)
    {
        $this->conditionBuilder->addCondition($logic, $negate, $collectionItems);

        return $this;
    }

    public function addRelated($logic, $negate, $relationship, $condition)
    {
        $this->conditionBuilder->addRelated($logic, $negate, $relationship, $condition);

        return $this;
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

    public function _and()
    {
        return $this->addCondition(func_get_args(), 'and', false);
    }

    public function _or()
    {
        return $this->addCondition(func_get_args(), 'or', false);
    }

    public function _xor()
    {
        return $this->addCondition(func_get_args(), 'xor', false);
    }

    public function _andNot()
    {
        return $this->addCondition(func_get_args(), 'and', true);
    }

    public function _orNot()
    {
        return $this->addCondition(func_get_args(), 'or', true);
    }

    public function _xorNot()
    {
        return $this->addCondition(func_get_args(), 'xor', true);
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
