<?php

class GroupOptimizerTest extends PHPUnit_Framework_TestCase
{
    protected $orm;
    protected $optimizer;
    protected $merger;

    public function setUp()
    {
        $this->orm = new \PHPixie\ORM(null);
        $this->merger = new \PHPixie\ORM\Mapper\Group\Optimizer\Merger($this->orm);
        $this->optimizer = new \PHPixie\ORM\Mapper\Group\Optimizer($this->orm, $this->merger);
    }

    public function testOptimize()
    {
        $this->assertOptimize(array(
            array (
                'and_b' => array (
                    'and.f1',
                    'and.f2',
                    array (
                        'and' => array (
                            'and.f3',
                            'or.f4',
                        ),
                    ),
                ),
            ),
        ),$this->builder()
            ->_and(function ($b) {
                $b
                    ->_and('b.f1', 1)
                    ->_and('b.f2', 1);
            })
            ->_and(function ($b) {
                $b
                    ->_and('b.f3', 1)
                    ->_or('b.f4', 1);
            })
        );

        $this->assertOptimize(array(
            array (
                'and_b' => array (
                    'and.f1',
                    'and.f2',
                    'or.f3',
                    'and.f4',
                ),
            ),
        ),$this->builder()
            ->_and(function ($b) {
                $b
                    ->_and('b.f1', 1)
                    ->_and('b.f2', 1);
            })
            ->_or(function ($b) {
                $b
                    ->_and('b.f3', 1)
                    ->_and('b.f4', 1);
            })
        );

        $this->assertOptimize(array(
            array (
                'and_b' => array (
                    'and.f1',
                    'and.f2',
                    'or.f3',
                ),
            ),
            array (
                'or_c' => array (
                    'and.f1',
                    'and.f2',
                ),
            ),
        ),$this->builder()
            ->_and('b.f1', 1)
            ->_and('b.f2', 2)
            ->_or('c.f1', 2)
            ->_and('c.f2', 2)
            ->_or('b.f3', 2)
        );

        $this->assertOptimize(array(
            array (
                'and_b' => array (
                    'and.f1',
                    'and.f2',
                ),
            ),
            array (
                'or_c' => array (
                    'and.f1',
                    'and.f2',
                ),
            ),
            array (
                'and_b' => array (
                    'and.f3',
                ),
            ),
        ),$this->builder()
            ->_and('b.f1', 1)
            ->_and('b.f2', 2)
            ->_or('c.f1', 2)
            ->_and('c.f2', 2)
            ->_and('b.f3', 2)
        );

        $this->assertOptimize(array(
            array (
                'and_b' => array (
                    array (
                        'and_la' => array (
                            'and.f1',
                        ),
                    ),
                    array (
                        'or_lal' => array (
                            'and.f2',
                        ),
                    ),
                ),
            )
        ),$this->builder()
            ->_and('b.la.f1', 1)
            ->_or('b.lal.f2', 2)
        );

        $this->assertOptimize(array(
            'and.!f'
        ),$this->builder()
            ->_and(function ($b) {
                $b->_orNot('f', 1);
            })
        );

        $this->assertOptimize(array(
            array(
                'and_b' => array (
                    'and.f',
                ),
            )
        ),$this->builder()
            ->_and(function ($b) {
                $b->_or('b.f', 1);
            })
        );

        $this->assertOptimize(array(
            array(
                'or_b' => array (
                    'and.f',
                ),
            )
        ),$this->builder()
            ->_or('b.f', 1)
        );

        $this->assertOptimize(array(
            array (
                '!and' => array (
                        'or.f',
                        'or.f1',
                ),
            ),
        ),$this->builder()
            ->_andNot(function ($b) {
                $b->_or('f', 1);
                $b->_or('f1', 1);
            })
        );

        $this->assertOptimize(array(
            array (
                'and_b' => array (
                        'and.f',
                        'or.f1',
                ),
            ),
        ),$this->builder()
            ->_and(function ($b) {
                $b->_or('b.f', 1);
                $b->_or('b.f1', 1);
            })
        );

        $this->assertOptimize(array(
            array (
                '!and_b' => array (
                    'and.f',
                    'or.f1',
                )
            ),
        ),$this->builder()
            ->_andNot(function ($b) {
                $b->_or('b.f', 1);
                $b->_or('b.f1', 1);
            })
        );

        $this->assertOptimize(array(
            array (
                'and_b' => array (
                    'and.!f',
                ),
            ),
        ),$this->builder()
            ->_andNot('b.f', 1)

        );

        $this->assertOptimize(array(
            array (
                '!and_b' => array (
                    'and.f',
                ),
            ),
        ),$this->builder()
            ->_andNot(function ($b) {
                $b->_or('b.f', 1);
            })
        );

        $this->assertOptimize(array(
            'and.!f'
        ),$this->builder()
            ->_andNot(function ($b) {
                $b->_andNot(function ($b) {
                    $b->_andNot(function ($b) {
                        $b->_or('f', 1);
                    });
                });
            })
        );

        $this->assertOptimize(array(
            array (
                'and_b' => array (
                    'and.f1',
                ),
            ),
            array (
                'and_c' => array (
                    'and.!f2',
                ),
            ),
            array (
                'or_b' => array (
                    'and.f3',
                )
            ),
        ),$this->builder()
            ->_and('b.f1', 1)
            ->_andNot('c.f2', 1)
            ->_or('b.f3', 2)
        );

        $this->assertOptimize(array(
            array (
                'and_a' => array (
                    'and.f1',
                ),
            ),
            array (
                'or_b' => array (
                    'and.f2',
                    'and.f3',
                ),
            ),
        ),$this->builder()
            ->_and('a.f1', 1)
            ->_or('b.f2', 1)
            ->_and('b.f3', 2)
        );

        $this->assertOptimize(array(
            array (
                'and_a' => array (
                    'and.f1',
                ),
            ),
            array (
                'or_b' => array (
                    'and.f2',
                    'and.f3',
                    'or.f6',
                ),
            ),
            array (
                'or_a' => array (
                    'and.f4',
                ),
            ),
            array (
                'and_b' => array (
                    'and.f5',
                ),
            ),
        ),$this->builder()
            ->_and('a.f1', 1)
            ->_or('b.f2', 1)
            ->_and('b.f3', 1)
            ->_or('a.f4', 1)
            ->_and('b.f5', 1)
            ->_or('b.f6', 1)
        );

        $this->assertOptimize(array(
            array (
                'and_a' => array (
                    'and.f1',
                ),
            ),
            array (
                'or_b' => array (
                    array (
                        '!and' => array (
                            'and.b1',
                            'and.b2',
                        ),
                    ),
                    'or.b3',
                )
            ),
        ),$this->builder()
            ->_and('a.f1', 1)
            ->_orNot(function ($builder) {
                $builder->_and('b.b1', 1)
                        ->_and('b.b2', 1);
            })
            ->_or('b.b3', 1)
        );

        $this->assertOptimize(array(
            array (
                'or_b' => array (
                    array (
                        'and_c' => array (
                            'and.b4',
                            'or.b7',
                        ),
                    ),
                    array (
                        'or_d' => array (
                            'and.b5',
                        ),
                    ),
                ),
            )
        ),$this->builder()
            ->_or('b.c.b4', 1)
            ->_or('b.d.b5', 1)
            ->_or('b.c.b7', 1)
        );

        $this->assertOptimize(array(
            array (
                'and_a' => array (
                    'and.f1',
                ),
            ),
            array (
                'or_b' => array (
                    array (
                        '!and' => array (
                            'and.b1',
                            'and.b2',
                        ),
                    ),
                    array (
                        'xor' => array (
                            'and.b3',
                            array (
                                'or_c' => array (
                                    'and.b4',
                                    'or.b7',
                                ),
                            ),
                            array (
                                'or_c' => array (
                                    'and.b5',
                                ),
                            ),
                            'and.b6',
                        ),
                    ),
                ),
            ),
        ),$this->builder()
            ->_and('a.f1', 1)
            ->_orNot(function ($builder) {
                $builder->_and('b.b1', 1)
                        ->_and('b.b2', 1);
            })
            ->_xor(function ($builder) {
                $builder->_and('b.b3', 1)
                        ->_or(function ($builder) {
                            $builder
                                    ->_or('b.c.b4', 1)
                                    ->_or('b.c.b5', 1)
                                    ->_and('b.b6', 1)
                                    ->_or('b.c.b7', 1);
                        });
            })

        );

        $this->assertOptimize(array(
              array (
                'and_b' =>
                    array (
                        'and.f1',
                        array (
                            'or_c' => array (
                                        'and.f2',
                                        'or.f5',
                                    ),
                        ),
                        'or.f4',
                    ),
                ),
        ),$this->builder()
            ->_and(function ($b) {
                $b
                    ->_and('b.f1',1)
                    ->_or('b.c.f2', 1);
            })
            ->_or(function ($b) {
                $b
                    ->_and('b.f4',1)
                    ->_or('b.c.f5', 1);
            })

        );

        $this->assertOptimize(array(
            array (
                'and_b' => array (
                    array (
                        'and_c' => array (
                            'and.f2',
                            'or.f5',
                        ),
                    ),
                    'or.f4',
                ),
            ),
        ),$this->builder()
            ->_and(function ($b) {
                $b
                    ->_or('b.c.f2', 1);
            })
            ->_or(function ($b) {
                $b
                    ->_xor('b.f4',1)
                    ->_or('b.c.f5', 1);
            })

        );

        $this->assertOptimize(array(
            array (
                'and_b' => array (
                    'and.f1',
                    'or.f2',
                    'xor.f3',
                ),
            ),
            array (
                'or_c.d' => array (
                    'and.f4',
                    array (
                        'and' => array (
                            'and.f5',
                            'or.f6',
                        ),
                    ),
                    'or.f7',
                ),
            ),
        ),$this->builder()
            ->_and(function ($b) {
                $b
                    ->_and('b.f1', 1)
                    ->_or('b.f2', 1)
                    ->_xor('b.f3', 1)
                    ->_or('c.d.f4', 1)
                    ->_and(function ($b) {
                        $b
                        ->_or('c.d.f5', 1)
                        ->_or('c.d.f6', 1);
                    });
            })

            ->_or(function ($b) {
                $b
                    ->_or('c.d.f7', 1);
            })
        );

        $this->assertOptimize(array(
                array (
                    'and_b' => array (
                        'and.f1',
                        'or.f2',
                    ),
                ),
                array (
                    'or_c' => array (
                        'and.f3',
                        array (
                            'or_d' => array (
                                'and.f3',
                                'or.f4',
                                'or.f5',
                                'or.f6',
                            ),
                        ),
                    ),
                ),
        ),$this->builder()
            ->_and(function ($b) {
                $b
                    ->_and('b.f1', 1)
                    ->_or('b.f2', 1)
                    ->_or('c.f3', 1)
                    ->_or(function ($b) {
                        $b
                            ->_or('c.d.f3', 1)
                            ->_or('c.d.f4', 1);
                    });
            })
            ->_or(function ($b) {
                $b->_or('c.d.f5', 1)
                ->_or('c.d.f6', 1);
            })
        );
        }
    }

    protected function extractConditions($conds)
    {
        $arr = array();

        foreach ($conds as $cond) {
            $prefix = $cond->logic;
            if ($cond instanceof \PHPixie\ORM\Conditions\Condition\Group\Relationship) {
                $prefix.= $prefix?'_':'';
                $prefix .= $cond->relationship;
            }

            $negated = $cond->negated() ? '!':'';

            if ($cond instanceof PHPixie\ORM\Conditions\Condition\Group) {
                $arr[] = array(
                    $negated.$prefix => $this->extractConditions($cond->conditions()));
            } else {
                $arr[] = $prefix.'.'.$negated.$cond->field;
            }
        }

        return($arr);
    }

    protected function assertOptimize($expected, $builder)
    {
        //$conds = $builder->getConditions();
        //$res = $this->extractConditions($conds);
        //var_export($res);

        $conds = $this->optimizer->optimize($builder->getConditions());
        //$res = $this->extractConditions($conds);
        //var_export($res);

        //$this->assertEquals($expected, $res);
    }

    protected function builder()
    {
        return new \PHPixie\ORM\Conditions\Builder($this->orm);
    }
}
