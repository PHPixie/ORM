<?php

namespace PHPixie\ORM\Models\Type\Database\Implementation;

class Query extends \PHPixie\ORM\Conditions\Builder\Proxy
            implements \PHPixie\ORM\Models\Type\Database\Query
{
    protected $values;
    protected $queryMapper;
    protected $queryMap;
    protected $container;
    protected $config;
    
    protected $relationshipProperties = array();

    protected $limit;
    protected $offset;
    
    protected $orderBy = array();

    public function __construct($values, $queryMapper, $queryMap, $container, $config)
    {
        parent::__construct($container);
        
        $this->container        = $container;
        $this->values           = $values;
        $this->queryMapper      = $queryMapper;
        $this->queryMap         = $queryMap;
        $this->config           = $config;
    }
    
    public function modelName()
    {
        return $this->config->model;
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
    
    public function getConditions()
    {
        return $this->container->getConditions();
    }

    public function find($preload = array())
    {
        return $this->planFind($preload)->execute();
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
        $preloads = $this->values->preload();
        
        foreach($preload as $item) {
            $preloads->add($item);
        }
        
        return $this->mapper->mapFind($this, $preloads);
    }


    
    public function update($updates)
    {
        $this->planUpdate($updates)->execute();
        return $this;
    }
    
    public function planUpdate($updates)
    {
        $update = $this->values->update();
        
        foreach($updates as $key => $value) {
            $update->set($key, $value);
        }
        
        return $this->mapper->mapUpdate($this, $update);
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
        return $this->planCount()->execute();
    }
    
    public function planCount()
    {
        return $this->mapper->mapCount($this);
    }
    
    
    public function getUpdateBuilder(){}
    public function planUpdateBuilder($b){}

    public function getRelationshipProperty($name)
    {
        if(!array_key_exists($name, $this->relationshipProperties)) {
            $property = $this->queryMap->property($this, $name);
            $this->relationshipProperties[$name] = $property;
        }
        
        return $this->relationshipProperties[$name];
    }
    
    public function __get($name)
    {
        return $this->getRelationshipProperty($name);
    }
    
}
