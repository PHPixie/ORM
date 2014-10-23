<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Mapper;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Mapper\Group
 */
class GroupTest extends \PHPixieTests\AbstractORMTest
{
    protected $relationships;
    protected $relationshipMap;
    protected $embedsGroupMapper;

    public function setUp()
    {
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        $this->relationshipMap = $this->quickMock('\PHPixie\ORM\Relationships\Map');
        $this->embedsGroupMapper = new \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Mapper\Group(
                                        $this->relationships,
                                        $this->relationshipMap
                                    );
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {

    }

    /**
     * @covers ::mapConditions
     * @covers ::<protected>
     */
    public function testMapCollection()
    {
        $collection = $this->quickMock('\PHPixie\ORM\Conditions\Condition\Collection');
        $this->setExpectedException('\PHPixie\ORM\Exception\Mapper');
        $this->embedsGroupMapper->mapConditions($this->getBuilder(), array($collection), 'test', $this->getPlan());
    }

    /**
     * @covers ::mapConditions
     * @covers ::<protected>
     */
    public function testMapUnsupportedCondition()
    {
        $collection = $this->quickMock('\PHPixie\ORM\Conditions\Condition\Collection');
        $this->setExpectedException('\PHPixie\ORM\Exception\Mapper');
        $this->embedsGroupMapper->mapConditions($this->getBuilder(), array(null), 'test', $this->getPlan());
    }

    /**
     * @covers ::mapConditions
     * @covers ::<protected>
     */
    public function testMapOperator()
    {
        $builder = $this->getBuilder();
        $plan = $this->getPlan();
        $field = 'name';
        $operator = $this->getOperator($field, '=', array('Pixie'), 'or', false);
        foreach(array('fairy', null) as $prefix)
        {
            $prefixedField = $this->getPrefixedField($field, $prefix);
            $this->method($builder, 'addOperatorCondition', null, array('or', false, $prefixedField, '=', array('Pixie')), 0);
            $this->embedsGroupMapper->mapConditions($builder, array($operator), 'test', $plan, $prefix);
        }
    }

    /**
     * @covers ::mapConditions
     * @covers ::<protected>
     */
    public function testMapRelationshipGroup()
    {
        $builder = $this->getBuilder();
        $plan = $this->getPlan();
        $group = $this->getRelationshipGroup(null, 'flower');

        $relationship = $this->getRelationship();
        $this->prepareRelationship($relationship, 'fairy', 'flower');

        $prefix = 'pixie';

        $this->method($relationship, 'mapRelationship', null, array($side, $builder, $group, $plan, $prefix), 0);
        $this->embedsGroupMapper->mapConditions($builder, array($group), 'fairy', $plan, $prefix);
    }

    /**
     * @covers ::mapConditions
     * @covers ::<protected>
     */
    public function testMapInvalidHandler()
    {
        $builder = $this->getBuilder();
        $plan = $this->getPlan();
        $group = $this->getRelationshipGroup(null, 'flower');

        $relationship = $this->getRelationship();
        $this->prepareRelationship($relationship, 'fairy', 'flower');

        $this->setExpectedException('\PHPixie\ORM\Exception\Mapper');
        $this->embedsGroupMapper->mapConditions($builder, array($group), 'fairy', $plan, 'pixie');
    }

    protected function prepareRelationship($relationship, $modelName, $relationsipName)
    {
        $side = $this->getSide('embedsOne');
        $this->method($this->relationshipMap, 'getSide', $side, array($modelName, $relationsipName), 0);
        $this->method($this->relationships, 'get', $relationship, array('embedsOne'), 0);
    }

    /**
     * @covers ::mapConditions
     * @covers ::<protected>
     */
    public function testMapConditionsGroup()
    {
        $this->mapConditionGroupTest();
    }

    /**
     * @covers ::mapConditionGroup
     * @covers ::<protected>
     */
    public function testMapConditionGroup()
    {
        $this->mapConditionGroupTest(true);
    }

    protected function mapConditionGroupTest($useMethod = false)
    {
        $builder = $this->getBuilder();
        $plan = $this->getPlan();
        $field = 'name';
        $operator = $this->getOperator($field, '=', array('Pixie'), 'or', false);
        $group = $this->getGroup(array($operator), 'or', 'false');
        foreach(array('fairy', null) as $prefix)
        {
            $prefixedField = $this->getPrefixedField($field, $prefix);
            $this->method($builder, 'startGroup', null, array('or', false), 0);
            $this->method($builder, 'addOperatorCondition', null, array('or', false, $prefixedField, '=', array('Pixie')), 1);
            $this->method($builder, 'endGroup', null, array('or', false), 2);
            if($useMethod) {
                $this->embedsGroupMapper->mapConditionGroup($builder, $group, 'test', $plan, $prefix);
            }else {
                $this->embedsGroupMapper->mapConditions($builder, array($group), 'test', $plan, $prefix);
            }
        }

    }

    protected function getPrefixedField($field, $prefix)
    {
        if($prefix === null)
            return $field;

        return $prefix.'.'.$field;
    }

    protected function getBuilder()
    {
        return $this->quickMock('\PHPixie\ORM\Conditions\Builder');
    }

    protected function getPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Step');
    }

    protected function getGroup($conditions, $logic = 'and', $negated = false)
    {
        $group = $this->abstractMock('\PHPixie\ORM\Conditions\Condition\Group');
        $this->method($group, 'conditions', $conditions, array());
        $this->method($group, 'logic', $logic, array());
        $this->method($group, 'negated', $negated, array());
        return $group;
    }

    protected function getRelationshipGroup($conditions, $relationship, $logic = 'and', $negated = false)
    {
        $group = $this->abstractMock('\PHPixie\ORM\Conditions\Condition\Group\Relationship');
        $this->method($group, 'conditions', $conditions, array());
        $this->method($group, 'relationship', $relationship, array());
        $this->method($group, 'logic', $logic, array());
        $this->method($group, 'negated', $negated, array());
        return $group;
    }

    protected function getOperator($field, $operator, $values, $logic = 'and', $negated = false)
    {
        $condition = $this->abstractMock('\PHPixie\ORM\Conditions\Condition\Operator');
        $condition->field = $field;
        $condition->operator = $operator;
        $condition->values = $values;
        $this->method($condition, 'logic', $logic, array());
        $this->method($condition, 'negated', $negated, array());
        return $condition;
    }

    protected function getSide($relationship)
    {
        $side = $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side');
        $this->method($side, 'relationshipType', $relationship, array());
        return $side;
    }

    protected function getEmbedsRelationship()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds');
    }

    protected function getRelationship()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship');
    }

}
