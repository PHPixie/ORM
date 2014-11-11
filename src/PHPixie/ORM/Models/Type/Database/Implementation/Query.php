<?php

namespace PHPixie\ORM\Models\Type\Database\Implementation;

class Query implements \PHPixie\ORM\Models\Type\Database\Query
{
    protected $conditionsBuilder;
    protected $values;
    protected $mapper;
    protected $relationshipMap;
    protected $config;
    
    protected $relationshipProperties = array();

    protected $limit;
    protected $offset;
    
    protected $orderBy = array();

    public function __construct($conditionsBuilder, $values, $mapper, $relationshipMap, $config)
    {
        $this->conditionBuilder = $conditionsBuilder;
        $this->values           = $values;
        $this->mapper           = $mapper;
        $this->relationhipMap   = $relationshipMap;
        $this->config           = $config;
    }
    
    public function modelName()
    {
        return $this->modelName;
    }
    
    public function limit($limit)
    {
        if (!is_numeric($limit))
            throw new \PHPixie\ORM\Exception\Query("Limit must be a number");

        $this->limit = $limit;
        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }
    
    public function clearLimit()
    {
        $this->limit = null;
        return $this;
    }

    public function offset($offset)
    {
        if (!is_numeric($offset))
            throw new \PHPixie\ORM\Exception\Query("Offset must be a number");

        $this->offset = $offset;
        return $this;
    }

    public function getOffset()
    {
        return $this->offset;
    }
    
    public function clearOffset()
    {
        $this->offset = null;
        return $this;
    }

    public function orderAscendingBy($field)
    {
        $this->orderBy[] = $this->values->orderBy($field, 'asc');
        return $this;
    }
    
    public function orderDescendingBy($field)
    {
        $this->orderBy[] = $this->values->orderBy($field, 'desc');
        return $this;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }
    
    public function clearOrderBy()
    {
        $this->orderBy = array();
        return $this;
    }

    public function find($preload = array())
    {
        return $this->planFind($preload);
    }
    
    public function findOne($preload = array())
    {
        $oldLimit = $this->getLimit();
        $this->limit(1);
        
        $loader = $this->find($preload);
        
        if($oldLimit !== null)
        {
            $this->limit($oldLimit);
        }else{
            $this->clearLimit();
        }
        
        if(!$loader->offsetExists(0))
            return null;

        return $loader->getByOffset(0);
    }
    
    public function planFind($preload = array())
    {
        return $this->mapper->mapFind($this, $preload);
    }


    
    public function update($data)
    {
        $this->updatePlan($data)->execute();
        return $this;
    }
    
    public function planUpdate($type, $data)
    {
        return $this->mapper->mapUpdate($this, $data);
    }
    
    
    
    public function delete()
    {
        $this->planDelete()->execute();
        return $this;
    }
    
    public function planDelete()
    {
        return $this->mapper->mapDelete($this);
    }
    
    public function count()
    {
        $this->planCount()->execute();
        return $this;
    }
    
    public function planCount()
    {
        return $this->mapper->mapCount($this);
    }

    public function getRelationshipProperty($name)
    {
        if(!array_key_exists($name, $this->relationshipProperties))
            $property = $this->relationshipMap->queryProperty($this, $name);
            $this->relationshipProperties[$name] = $property;
        }
        
        return $this->relationshipProperties[$name];
    }
    
    public function __get($name)
    {
        return $this->getRelationshipProperty($name);
    }
    ////////////////////////////
    protected function addCondition($args, $logic, $negate)
    {
        $this->builder->addCondition($logic, $negate, $args);
        return $this;
    }
    
    protected function startBuilderGroup($logic, $negate)
    {
        $this->builder->startConditionGroup($logic, $negate);
        return $this;
    }
    
    protected function addCollectionCondition($items, $logic, $negate)
    {
        $this->builder->addCollection($logic, $negate, $items);
        return $this;
    }

    protected function addRelationshipCondition($relationship, $items, $logic, $negate)
    {
        $this->builder->startRelationshipCondition($logic, $negate, $relationship, $items);
        return $this;
    }
    
    protected function startRelationshipGroup($logic, $negate)
    {
        $this->builder->startRelationshipGroup($logic, $negate);
        return $this;
    }

    public function endGroup()
    {
        $this->builder->endGroup
    }
////////////////
    public function where()
    {
        return $this->addCondition(func_get_args(), 'and', false);
    }

    public function andWhere()
    {
        return $this->addCondition(func_get_args(), 'and', false);
    }

    public function orWhere()
    {
        return $this->addCondition(func_get_args(), 'or', false);
    }

    public function xorWhere()
    {
        return $this->addCondition(func_get_args(), 'xor', false);
    }

    public function whereNot()
    {
        return $this->addCondition(func_get_args(), 'and', true);
    }

    public function andWhereNot()
    {
        return $this->addCondition(func_get_args(), 'and', true);
    }

    public function orWhereNot()
    {
        return $this->addCondition(func_get_args(), 'or', true);
    }

    public function xorWhereNot()
    {
        return $this->addCondition(func_get_args(), 'xor', true);
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

    public function _not()
    {
        return $this->addCondition(func_get_args(), 'and', true);
    }

    public function andNot()
    {
        return $this->addCondition(func_get_args(), 'and', true);
    }

    public function orNot()
    {
        return $this->addCondition(func_get_args(), 'or', true);
    }

    public function xorNot()
    {
        return $this->addCondition(func_get_args(), 'xor', true);
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

    public function endGroup()
    {
        return $this->endConditionGroup();
    }

    public function in($items)
    {
        return $this->addCollectionCondition($items, 'and', false);
    }
    
    public function andIn($items)
    {
        return $this->addCollectionCondition($items, 'and', false);
    }
    
    public function orIn($items)
    {
        return $this->addCollectionCondition($items, 'or', false);
    }
    
    public function xorIn($items)
    {
        return $this->addCollectionCondition($items, 'xor', false);
    }
    
    public function notIn($items)
    {
        return $this->addCollectionCondition($items, 'and', true);
    }
    
    public function andNotIn()
    {
        return $this->addCollectionCondition($items, 'and', true);
    }
    
    public function orNotIn($items)
    {
        return $this->addCollectionCondition($items, 'or', true);
    }
    
    public function xorNotIn($items)
    {
        return $this->addCollectionCondition($items, 'xor', true);
    }
    
    public function relatedTo($relationship, $items)
    {
        return $this->addRelationshipCondition($relationship, $items, 'and', false);
    }

    public function andRelatedTo($relationship, $items)
    {
        return $this->addRelationshipCondition($relationship, $items, 'and', false);
    }

    public function orRelatedTo($relationship, $items)
    {
        return $this->addRelationshipCondition($relationship, $items, 'or', false);
    }
    
    public function xorRelatedTo($relationship, $items)
    {
        return $this->addRelationshipCondition($relationship, $items, 'xor', false);
    }
    
    public function notRelatedTo($relationship, $items)
    {
        return $this->addRelationshipCondition($relationship, $items, 'and', true);
    }
    
    public function andNotRelatedTo($relationship, $items)
    {
        return $this->addRelationshipCondition($relationship, $items, 'and', true);
    }
    
    public function orNotRelatedTo($relationship, $items)
    {
        return $this->addRelationshipCondition($relationship, $items, 'or', true);
    }
    
    public function xorNotRelatedTo($relationship, $items)
    {
        return $this->addRelationshipCondition($relationship, $items, 'xor', true);
    }
    
    public function startRelatedToGroup($relationship)
    {
        return $this->startRelationshipGroup($relationship, 'and', false);
    }
    
    public function startAndRelatedToGroup($relationship)
    {
         return $this->startRelationshipGroup($relationship, 'and', false);
    }
    
    public function startOrRelatedToGroup($relationship)
    {
         return $this->startRelationshipGroup($relationship, 'or', false);
    }
    
    public function startXorRelatedToGroup($relationship)
    {
         return $this->startRelationshipGroup($relationship, 'xor', false);
    }
    
    public function startNotRelatedToGroup($relationship)
    {
         return $this->startRelationshipGroup($relationship, 'and', true);
    }
    
    public function startAndNotRelatedToGroup($relationship)
    {
         return $this->startRelationshipGroup($relationship, 'and', true);
    }
    
    public function startOrNotRelatedToGroup($relationship)
    {
         return $this->startRelationshipGroup($relationship, 'or', true);
    }
    
    public function startXorNotRelatedToGroup($relationship)
    {
         return $this->startRelationshipGroup($relationship, 'xor', true);
    }
    
    
}
