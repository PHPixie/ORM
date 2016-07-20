<?php

namespace PHPixie\ORM\Wrappers\Type\Database;

class Query extends \PHPixie\ORM\Conditions\Builder\Proxy
            implements \PHPixie\ORM\Models\Type\Database\Query
{
    /**
     * @type \PHPixie\ORM\Drivers\Driver\PDO\Query|\PHPixie\ORM\Drivers\Driver\Mongo\Query
     */
    protected $query;
    
    public function __construct($query)
    {
        parent::__construct($query);
        $this->query = $query;
    }
    
    public function modelName()
    {
        return $this->query->modelName();
    }
    
    public function limit($limit)
    {
        $this->query->limit($limit);
        return $this;
    }
    
    public function getLimit()
    {
        return $this->query->getLimit();
    }
    
    public function clearLimit()
    {
        $this->query->clearLimit();
        return $this;
    }
    
    
    public function offset($offset)
    {
        $this->query->offset($offset);
        return $this;
    }
    
    public function getOffset()
    {
        return $this->query->getOffset();
    }
    
    public function clearOffset()
    {
        $this->query->clearOffset();
        return $this;
    }
    
    public function orderAscendingBy($field)
    {
        $this->query->orderAscendingBy($field);
        return $this;
    }
    
    public function orderDescendingBy($field)
    {
        $this->query->orderDescendingBy($field);
        return $this;
    }
    
    public function getOrderBy()
    {
        return $this->query->getOrderBy();
    }
    
    public function clearOrderBy()
    {
        $this->query->clearOrderBy();
        return $this;
    }
    
    public function getConditions()
    {
        return $this->query->getConditions();
    }
    
    public function planFind($preload = array(), $fields = null)
    {
        return $this->query->planFind($preload, $fields);
    }
    
    public function find($preload = array(), $fields = null)
    {
        return $this->query->find($preload, $fields);
    }
    
    public function findOne($preload = array(), $fields = null)
    {
        return $this->query->findOne($preload, $fields);
    }
    
    public function planDelete()
    {
        return $this->query->planDelete();
    }
    
    public function delete()
    {
        $this->query->delete();
        return $this;
    }
    
    public function planUpdate($data)
    {
        return $this->query->planUpdate($data);
    }
    
    public function update($data)
    {
        $this->query->update($data);
        return $this;
    }
    
    public function planUpdateValue($update)
    {
        return $this->query->planUpdateValue($update);
    }
    
    public function getUpdateBuilder()
    {
        return $this->query->getUpdateBuilder();
    }
    
    public function planCount()
    {
        return $this->query->planCount();
    }
    
    public function count()
    {
        return $this->query->count();
    }
    
    public function getRelationshipProperty($name)
    {
        return $this->query->getRelationshipProperty($name);
    }
    
    public function __get($name)
    {
        return $this->query->__get($name);
    }
    
    public function __call($method, $params)
    {
        return $this->query->__call($method, $params);
    }
}
