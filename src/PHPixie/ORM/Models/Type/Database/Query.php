<?php

namespace PHPixie\ORM\Models\Type\Database;

use PHPixie\ORM\Loaders\Loader;
use PHPixie\ORM\Plans\Plan;
use PHPixie\ORM\Relationships\Relationship\Property;
use PHPixie\ORM\Values\Update\Builder as UpdateBuilder;

interface Query extends \PHPixie\ORM\Conditions\Condition\In\Item,
\PHPixie\ORM\Conditions\Builder
{
    /**
     * @return static
     */
    public function limit($limit);

    /**
     * @return static
     */
    public function getLimit();

    /**
     * @return static
     */
    public function clearLimit();

    /**
     * @return static
     */
    public function offset($offset);

    /**
     * @return static
     */
    public function getOffset();

    /**
     * @return static
     */
    public function clearOffset();

    /**
     * @param $field
     * @param $direction
     *
     * @return static
     */
    public function orderBy($field, $direction);

    /**
     * @return static
     */
    public function orderAscendingBy($field);

    /**
     * @return static
     */
    public function orderDescendingBy($field);

    /**
     * @return static
     */
    public function getOrderBy();

    /**
     * @return static
     */
    public function clearOrderBy();

    /**
     * @param array $preload
     * @return Plan
     */
    public function planFind($preload = array(), $fields = null);

    /**
     * @param array $preload
     * @return Loader
     */
    public function find($preload = array(), $fields = null);

    /**
     * @param array $preload
     * @return \PHPixie\ORM\Models\Type\Database\Implementation\Entity
     */
    public function findOne($preload = array(), $fields = null);

    /**
     * @return Plan
     */
    public function planDelete();

    /**
     * @return void
     */
    public function delete();

    /**
     * @return UpdateBuilder
     */
    public function getUpdateBuilder();

    /**
     * @param $data
     * @return Plan
     */
    public function planUpdate($data);

    /**
     * @param $update
     * @return Plan
     */
    public function planUpdateValue($update);

    /**
     * @param $data
     * @return void
     */
    public function update($data);

    /**
     * @return Plan
     */
    public function planCount();

    /**
     * @return int
     */
    public function count();

    /**
     * @param $name
     * @return Property
     */
    public function getRelationshipProperty($name);

    /**
     * @return array
     */
    public function getConditions();

    public function __get($name);
    public function __call($method, $params);
}
