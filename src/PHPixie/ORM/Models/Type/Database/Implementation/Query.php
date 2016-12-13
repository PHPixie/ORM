<?php

namespace PHPixie\ORM\Models\Type\Database\Implementation;

class Query extends \PHPixie\ORM\Conditions\Builder\Proxy
            implements \PHPixie\ORM\Models\Type\Database\Query
{
    /**
     * @type \PHPixie\ORM\Values
     */
    protected $values;
    /**
     * @type \PHPixie\ORM\Mappers\Query
     */
    protected $queryMapper;
    /**
     * @type \PHPixie\ORM\Maps\Map\Property\Query
     */
    protected $queryPropertyMap;
    /**
     * @type \PHPixie\ORM\Conditions\Builder\Container
     */
    protected $container;
    protected $config;
    
    protected $relationshipProperties;

    protected $limit;
    protected $offset;
    
    protected $orderBy = array();

    public function __construct($values, $queryMapper, $queryPropertyMap, $container, $config)
    {
        parent::__construct($container);
        
        $this->container        = $container;
        $this->values           = $values;
        $this->queryMapper      = $queryMapper;
        $this->queryPropertyMap = $queryPropertyMap;
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

    public function orderBy($field, $direction)
    {
        $this->orderBy[] = $this->values->orderBy($field, $direction);
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

    public function find($preload = array(), $fields = null)
    {
        return $this->planFind($preload, $fields)->execute();
    }

    /**
     * @param array $preload
     * @return null|Entity
     * @throws \PHPixie\ORM\Exception\Query
     */
    public function findOne($preload = array(), $fields = null)
    {
        $oldLimit = $this->getLimit();
        $this->limit(1);
        
        $loader = $this->find($preload, $fields);
        
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
    
    public function planFind($preload = array(), $fields = null)
    {
        $preloads = $this->values->preload();
        
        foreach($preload as $item => $options) {
            if(is_numeric($item)) {
                $item = $options;
                $options = array();
            }
            
            $preloads->add($item, $options);
        }
        
        return $this->queryMapper->mapFind($this, $preloads, $fields);
    }


    
    public function update($updates)
    {
        $this->planUpdate($updates)->execute();
        return $this;
    }
    
    public function planUpdate($updates)
    {
        $update = $this->values->update($this);
        
        foreach($updates as $key => $value) {
            $update->set($key, $value);
        }
        
        return $this->planUpdateValue($update);
    }
    
    public function delete()
    {
        $this->planDelete()->execute();
        return $this;
    }
    
    public function planDelete()
    {
        return $this->queryMapper->mapDelete($this);
    }
    
    public function count()
    {
        return $this->planCount()->execute();
    }
    
    public function planCount()
    {
        return $this->queryMapper->mapCount($this);
    }
    
    public function getUpdateBuilder()
    {
        return $this->values->updateBuilder($this);
    }
    
    public function planUpdateValue($update){
        return $this->queryMapper->mapUpdate($this, $update);
    }

    public function getRelationshipProperty($name)
    {
        $this->requirePropertyNames();
        
        if (!array_key_exists($name, $this->relationshipProperties)) {
            throw new \PHPixie\ORM\Exception\Relationship("Relationship property '$name' is not defined for '{$this->modelName()}'");
        }
        
        return $this->relationshipProperty($name);
    }
    
    protected function relationshipProperty($name)
    {
        $property = $this->relationshipProperties[$name];
        
        if($property === null) {
            $property = $this->queryPropertyMap->property($this, $name);
            $this->relationshipProperties[$name] = $property;
        }
        
        return $this->relationshipProperties[$name];
    }
    
    public function __get($name)
    {
        return $this->getRelationshipProperty($name);
    }
    
    public function __call($name, $params)
    {
        $this->requirePropertyNames();
        
        if (array_key_exists($name, $this->relationshipProperties)) {
            $property = $this->relationshipProperty($name);
            return call_user_func_array($property, $params);
        }
        
        return parent::__call($name, $params);
    }
    
    protected function requirePropertyNames()
    {
        if ($this->relationshipProperties === null) {
            $propertyNames = $this->queryPropertyMap->getPropertyNames($this->modelName());
            $this->relationshipProperties = array_fill_keys($propertyNames, null);
        }
    }
}
