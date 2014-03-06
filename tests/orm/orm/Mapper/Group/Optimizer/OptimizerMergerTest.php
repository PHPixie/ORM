<?php

class OptimizerMergerTest extends PHPUnit_Framework_TestCase
{
    protected $merger;
    protected $orm;

    protected $logicPrecedance = array(
        'and' => 2,
        'xor' => 1,
        'or'  => 0
    );

    public function setUp()
    {
        $this->orm = new \PHPixie\ORM(null);
        $this->merger = new \PHPixie\ORM\Mapper\Group\Optimizer\Merger($this->orm);
    }

    public function testCommonRelationShip()
    {
        $this->assertEquals(null, $this->merger->commonRelationship('aaa.bbbb', 'sdsd.dsd'));
        $this->assertEquals('aaa.bbb', $this->merger->commonRelationship('aaa.bbb.dfd', 'aaa.bbb.ggg'));
        $this->assertEquals('aaa.bbb', $this->merger->commonRelationship('aaa.bbb.dfd', 'aaa.bbb'));
        $this->assertEquals('aaa', $this->merger->commonRelationship('aaa.bbb', 'aaa.bbd'));
        $this->assertEquals(null, $this->merger->commonRelationship('aaa', null));
    }

    public function testFindMergeTarget()
    {
        $this->assertMergeTarget(null, 'b', 'and', $this->builder()
                                                        ->_and('a.f', 1)
                            );

        $this->assertMergeTarget(1, 'b', 'and', $this->builder()
                                                        ->_and('a.f', 1)
                                                        ->_and('b.f',1)
                            );

        $this->assertMergeTarget(null, null, 'and', $this->builder()
                                                        ->_and('a.f', 1)
                                                        ->_and('b.f',1)
                            );

        $this->assertMergeTarget(null, 'b', 'or', $this->builder()
                                                        ->_or('a.f', 1)
                                                        ->_and('b.f',1)
                            );

        $this->assertMergeTarget(null, 'b', 'and', $this->builder()
                                                        ->_and('b.f', 1)
                                                        ->_and('b.f', 1)
                                                        ->_or('a.f', 1)
                                                        ->_and('c.f',1)
                            );

        $this->assertMergeTarget(2, 'b', 'xor', $this->builder()
                                                        ->_and('b.f', 1)
                                                        ->_xor('b.f', 1)
                                                        ->_or('b.f', 1)
                            );

        $this->assertMergeTarget(1, 'b', 'or', $this->builder()
                                                        ->_or('f', 1)
                                                        ->_or(function ($builder) {
                                                            $builder->_and('c', 1);
                                                        })
                                                        ->_or('ff', 1)
                            );

        $this->assertMergeTarget(null, null, 'or', $this->builder()
                                                        ->_or('f', 1)
                                                        ->_or(function ($builder) {
                                                            $builder->_and('c', 1);
                                                        })
                                                        ->_or('ff', 1)
                            );

    }

    protected function assertMergeTarget($expected, $rel, $logic, $builder)
    {
        $this->assertEquals($expected, $this->merger->findMergeTarget(
                                        $builder->getConditions(),
                                        $this->operator($rel, $logic),
                                        $this->logicPrecedance
                        ));
    }

    public function testMergeGroup()
    {
        $left = $this->group('la');
        $right = $this->group('al');
        $this->assertMerge(false, 0, $left, $right);

        $left = $this->group('la', true);
        $right = $this->group('la');
        $this->assertMerge(false, 0, $left, $right);

        $left = $this->group('la');
        $right = $this->group('la', true);
        $this->assertMerge(false, 0, $left, $right);

        $left = $this->group('la');
        $right = $this->group('la');
        $this->assertMerge(true, 0, $left, $right);

        $left = $this->group('la', false, 'and', $this->builder()->_and('f', 1)->_and('f', 2)->getConditions());
        $right = $this->group('la', false, 'or', $this->builder()->_and('f', 1)->_and('f', 2)->getConditions());
        $this->assertMerge(true, 4, $left, $right);

        $left = $this->group('la', false, 'and', $this->builder()->_and('f', 1)->_and('f', 2)->getConditions());
        $right = $this->group('la', false, 'and', $this->builder()->_or('f', 1)->_or('f', 2)->getConditions());
        $this->assertMerge(false, 2, $left, $right);
    }

    protected function assertMerge($expected, $expectedLeftCount, $left, $right)
    {
        $this->assertEquals($expected, $this->merger->mergeGroups($left, $right, $this->logicPrecedance));
        $this->assertEquals($expectedLeftCount, count($left->conditions()));
    }

    /*
    public function testSetRelationship()
    {
        $items = $this->builder()
                                ->_and("test.me.f1", 1)
                                ->_and("test.me.hello.f1", 1)
                                ->_or("test.me.test2.hello2.f2", 1)
                            ->getConditions();
        $group = $this->group(null, false, 'and', $items);
        $this->merger->setRelationship($group, 'test.me');
        $items = $group->conditions();
        $this->assertEquals(null, $items[0]->relationship);
        $this->assertEquals('hello', $items[1]->relationship);
        $this->assertEquals('test2.hello2', $items[2]->relationship);
    }*/

    protected function assertMapperException($callback)
    {
        $except = false;
        try {
            $callback();
        } catch (\PHPixie\ORM\Exception\Mapper $e) {
            $except = true;
        }

        $this->assertEquals(true, $except);
    }

    protected function group($relationship=null, $negated = false, $logic = 'and', $conditions = array())
    {
        $group = new \PHPixie\ORM\Conditions\Condition\Group;
        if ($negated)
            $group->negate();
        $group->logic = $logic;
        $group->relationship = $relationship;
        $group->setConditions($conditions);

        return $group;
    }

    protected function builder()
    {
        return new \PHPixie\ORM\Conditions\Builder($this->orm);
    }

    protected function operator($rel, $logic, $field = 'a')
    {
        $operator = new \PHPixie\ORM\Conditions\Condition\Operator($field, '=', 1);
        $operator->relationship = $rel;
        $operator->logic = $logic;

        return $operator;
    }
}
