<?php

class GroupMapperTest extends PHPUnit_Framework_TestCase
{
    protected $orm;
    protected $mapper;

    public function setUp()
    {
        $this->orm = new \PHPixie\ORM(null);
        $this->mapper = new PHPixie\ORM\Mapper\Group($this->orm);
    }

    public function testMap()
    {
    /*
        $builder = $this->builder()
                                ->_and('b.f1', 1)
                                ->_and('b.f1', 1)
                                ->_or('a.f', 2)
                                ->_and('b.f', 2)

                                ->_or('a.f', 2)
                                ->_and('b.f', 2)
                                ->_and('c.f', 2)

                                ->_or('k.f2', 1)
                                ;
        $this->assertMap($builder);
        */
    }

    protected function extractConditions($conds)
    {
        $arr = array();

        foreach ($conds as $cond) {
            $prefix = $cond->logic;
            if ($cond->relationship !== null) {
                $prefix.= $prefix?'_':'';
                $prefix .= $cond->relationship;
            }

            if ($cond instanceof PHPixie\ORM\Conditions\Condition\Group) {
                $arr[] = array(
                    $prefix => $this->extractConditions($cond->conditions()));
            } else {
                $arr[] = $prefix.'.'.$cond->field;
            }
        }

        return($arr);
    }

    protected function assertMap($builder)
    {
        $conds = $this->mapper->map($builder->getConditions());
        print_r($this->extractConditions($conds));
    }

    protected function builder()
    {
        return new \PHPixie\ORM\Conditions\Builder($this->orm);
    }
}
