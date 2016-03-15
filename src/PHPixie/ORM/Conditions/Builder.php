<?php

namespace PHPixie\ORM\Conditions;

interface Builder extends \PHPixie\Database\Conditions\Builder
{
    /**
     * @return static
     */
    public function addWhereOperatorCondition($logic, $negate, $field, $operator, $values);

    /**
     * @return static
     */
    public function addWherePlaceholder($logic = 'and', $negate = false, $allowEmpty = true);

    /**
     * @return static
     */
    public function startWhereConditionGroup($logic = 'and', $negate = false);

    /**
     * @return static
     */
    public function addWhereCondition($logic, $negate, $condition);

    /**
     * @return static
     */
    public function buildWhereCondition($logic, $negate, $args);

    /**
     * @return static
     */
    public function where();

    /**
     * @return static
     */
    public function andWhere();

    /**
     * @return static
     */
    public function orWhere();

    /**
     * @return static
     */
    public function xorWhere();

    /**
     * @return static
     */
    public function whereNot();

    /**
     * @return static
     */
    public function andWhereNot();

    /**
     * @return static
     */
    public function orWhereNot();

    /**
     * @return static
     */
    public function xorWhereNot();

    /**
     * @return static
     */
    public function startWhereGroup();

    /**
     * @return static
     */
    public function startAndWhereGroup();

    /**
     * @return static
     */
    public function startOrWhereGroup();

    /**
     * @return static
     */
    public function startXorWhereGroup();

    /**
     * @return static
     */
    public function startWhereNotGroup();

    /**
     * @return static
     */
    public function startAndWhereNotGroup();

    /**
     * @return static
     */
    public function startOrWhereNotGroup();

    /**
     * @return static
     */
    public function startXorWhereNotGroup();

    /**
     * @return static
     */
    public function endWhereGroup();

    /**
     * @return static
     */
    public function addInCondition($logic, $negate, $items);

    /**
     * @return static
     */
    public function in($items);

    /**
     * @return static
     */
    public function andIn($items);

    /**
     * @return static
     */
    public function orIn($items);

    /**
     * @return static
     */
    public function xorIn($items);

    /**
     * @return static
     */
    public function notIn($items);

    /**
     * @return static
     */
    public function andNotIn($items);

    /**
     * @return static
     */
    public function orNotIn($items);

    /**
     * @return static
     */
    public function xorNotIn($items);

    /**
     * @return static
     */
    public function addRelatedToCondition($logic, $negate, $relationship, $condition = null);

    /**
     * @return static
     */
    public function startRelatedToConditionGroup($relationship, $logic = 'and', $negate = false);

    /**
     * @return static
     */
    public function relatedTo($relationship, $items = null);

    /**
     * @return static
     */
    public function andRelatedTo($relationship, $items = null);

    /**
     * @return static
     */
    public function orRelatedTo($relationship, $items = null);

    /**
     * @return static
     */
    public function xorRelatedTo($relationship, $items = null);

    /**
     * @return static
     */
    public function notRelatedTo($relationship, $items = null);

    /**
     * @return static
     */
    public function andNotRelatedTo($relationship, $items = null);

    /**
     * @return static
     */
    public function orNotRelatedTo($relationship, $items = null);

    /**
     * @return static
     */
    public function xorNotRelatedTo($relationship, $items = null);

    /**
     * @return static
     */
    public function startRelatedToGroup($relationship);

    /**
     * @return static
     */
    public function startAndRelatedToGroup($relationship);

    /**
     * @return static
     */
    public function startOrRelatedToGroup($relationship);

    /**
     * @return static
     */
    public function startXorRelatedToGroup($relationship);

    /**
     * @return static
     */
    public function startNotRelatedToGroup($relationship);

    /**
     * @return static
     */
    public function startAndNotRelatedToGroup($relationship);

    /**
     * @return static
     */
    public function startOrNotRelatedToGroup($relationship);

    /**
     * @return static
     */
    public function startXorNotRelatedToGroup($relationship);
}