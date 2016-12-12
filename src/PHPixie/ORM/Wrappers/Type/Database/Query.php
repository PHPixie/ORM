<?php

namespace PHPixie\ORM\Wrappers\Type\Database;

class Query extends \PHPixie\ORM\Conditions\Builder\Proxy
            implements \PHPixie\ORM\Models\Type\Database\Query
{
    /**
     * @type \PHPixie\ORM\Drivers\Driver\PDO\Query|\PHPixie\ORM\Drivers\Driver\Mongo\Query
     */
    protected $query;

    /**
     * Query constructor.
     *
     * @param $query
     */
    public function __construct($query)
    {
        parent::__construct($query);
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function modelName()
    {
        return $this->query->modelName();
    }

    /**
     * @param $limit
     *
     * @return $this
     * @throws \PHPixie\ORM\Exception\Query
     */
    public function limit($limit)
    {
        $this->query->limit($limit);
        return $this;
    }

    /**
     * @return static
     */
    public function getLimit()
    {
        return $this->query->getLimit();
    }

    /**
     * @return $this
     */
    public function clearLimit()
    {
        $this->query->clearLimit();
        return $this;
    }


    /**
     * @param $offset
     *
     * @return $this
     * @throws \PHPixie\ORM\Exception\Query
     */
    public function offset($offset)
    {
        $this->query->offset($offset);
        return $this;
    }

    /**
     * @return static
     */
    public function getOffset()
    {
        return $this->query->getOffset();
    }

    /**
     * @return $this
     */
    public function clearOffset()
    {
        $this->query->clearOffset();
        return $this;
    }

    /**
     * @param $field
     * @param $direction
     *
     * @return $this
     */
    public function orderBy($field, $direction)
    {
        $this->query->orderBy($field, $direction);
        return $this;
    }

    /**
     * @param $field
     *
     * @return $this
     */
    public function orderAscendingBy($field)
    {
        $this->query->orderAscendingBy($field);
        return $this;
    }

    /**
     * @param $field
     *
     * @return $this
     */
    public function orderDescendingBy($field)
    {
        $this->query->orderDescendingBy($field);
        return $this;
    }

    /**
     * @return array|static
     */
    public function getOrderBy()
    {
        return $this->query->getOrderBy();
    }

    /**
     * @return $this
     */
    public function clearOrderBy()
    {
        $this->query->clearOrderBy();
        return $this;
    }

    /**
     * @return array
     */
    public function getConditions()
    {
        return $this->query->getConditions();
    }

    /**
     * @param array $preload
     * @param null  $fields
     *
     * @return \PHPixie\ORM\Plans\Plan
     */
    public function planFind($preload = array(), $fields = null)
    {
        return $this->query->planFind($preload, $fields);
    }

    /**
     * @param array $preload
     * @param null  $fields
     *
     * @return \PHPixie\ORM\Loaders\Loader|void
     */
    public function find($preload = array(), $fields = null)
    {
        return $this->query->find($preload, $fields);
    }

    /**
     * @param array $preload
     * @param null  $fields
     *
     * @return null|\PHPixie\ORM\Models\Type\Database\Implementation\Entity
     * @throws \PHPixie\ORM\Exception\Query
     */
    public function findOne($preload = array(), $fields = null)
    {
        return $this->query->findOne($preload, $fields);
    }

    /**
     * @return \PHPixie\ORM\Plans\Plan
     */
    public function planDelete()
    {
        return $this->query->planDelete();
    }

    /**
     * @return $this
     */
    public function delete()
    {
        $this->query->delete();
        return $this;
    }

    /**
     * @param $data
     *
     * @return \PHPixie\ORM\Plans\Plan
     */
    public function planUpdate($data)
    {
        return $this->query->planUpdate($data);
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function update($data)
    {
        $this->query->update($data);
        return $this;
    }

    /**
     * @param $update
     *
     * @return \PHPixie\ORM\Plans\Plan
     */
    public function planUpdateValue($update)
    {
        return $this->query->planUpdateValue($update);
    }

    /**
     * @return \PHPixie\ORM\Values\Update\Builder
     */
    public function getUpdateBuilder()
    {
        return $this->query->getUpdateBuilder();
    }

    /**
     * @return \PHPixie\ORM\Plans\Plan
     */
    public function planCount()
    {
        return $this->query->planCount();
    }

    /**
     * @return int|void
     */
    public function count()
    {
        return $this->query->count();
    }

    /**
     * @param $name
     *
     * @return \PHPixie\ORM\Relationships\Relationship\Property
     * @throws \PHPixie\ORM\Exception\Relationship
     */
    public function getRelationshipProperty($name)
    {
        return $this->query->getRelationshipProperty($name);
    }

    /**
     * @param $name
     *
     * @return \PHPixie\ORM\Relationships\Relationship\Property
     */
    public function __get($name)
    {
        return $this->query->__get($name);
    }

    /**
     * @param $method
     * @param $params
     *
     * @return mixed
     */
    public function __call($method, $params)
    {
        return $this->query->__call($method, $params);
    }
}
