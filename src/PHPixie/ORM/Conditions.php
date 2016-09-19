<?php

namespace PHPixie\ORM;

/**
 * Class Conditions
 * @package PHPixie\ORM
 */
class Conditions
{
    /**
     * @type \PHPixie\ORM\Builder
     */
    protected $ormBuilder;

    /**
     * Conditions constructor.
     * @param \PHPixie\ORM\Builder $ormBuilder
     */
    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }

    /**
     * @param $modelName
     * @param string $defaultOperator
     * @param bool $allowEmpty
     * @return Conditions\Condition\Collection\Placeholder
     */
    public function placeholder($modelName, $defaultOperator = '=', $allowEmpty = true)
    {
        $container = $this->container($modelName, $defaultOperator);
        return new \PHPixie\ORM\Conditions\Condition\Collection\Placeholder($container, $allowEmpty);
    }

    /**
     * @param $field
     * @param $operator
     * @param $values
     * @return Conditions\Condition\Field\Operator
     */
    public function operator($field, $operator, $values)
    {
        return new \PHPixie\ORM\Conditions\Condition\Field\Operator($field, $operator, $values);
    }

    /**
     * @return Conditions\Condition\Collection\Group
     */
    public function group()
    {
        return new \PHPixie\ORM\Conditions\Condition\Collection\Group();
    }

    /**
     * @param $relationship
     * @return Conditions\Condition\Collection\RelatedTo\Group
     */
    public function relatedToGroup($relationship)
    {
        return new \PHPixie\ORM\Conditions\Condition\Collection\RelatedTo\Group($relationship);
    }

    /**
     * @param $modelName
     * @param array $items
     * @return Conditions\Condition\In
     */
    public function in($modelName, $items = array())
    {
        return new \PHPixie\ORM\Conditions\Condition\In($modelName, $items);
    }

    /**
     * @param $field
     * @param $subquery
     * @param $subqueryField
     * @return Conditions\Condition\Field\Subquery
     */
    public function subquery($field, $subquery, $subqueryField)
    {
        return new \PHPixie\ORM\Conditions\Condition\Field\Subquery($field, $subquery, $subqueryField);
    }

    /**
     * @param $modelName
     * @param string $defaultOperator
     * @return Conditions\Builder\Container
     */
    public function container($modelName, $defaultOperator = '=')
    {
        return new \PHPixie\ORM\Conditions\Builder\Container(
            $this,
            $this->ormBuilder->maps()->relationship(),
            $modelName,
            $defaultOperator
        );
    }

}
