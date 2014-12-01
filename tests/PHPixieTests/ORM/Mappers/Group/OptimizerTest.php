<?php

namespace PHPixieTests\ORM\Mappers\Group;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Group\Optimizer
 */
class OptimizerTest extends \PHPixieTests\AbstractORMTest
{
    protected $conditions;
    protected $merger;

    public function setUp()
    {
        $this->conditions = new \PHPixie\ORM\Conditions();
        $this->optimizer = new \PHPixie\ORM\Mappers\Group\Optimizer($this->conditions, $this->merger);
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::optimize
     * @covers ::<protected>
     */
    public function testOptimize()
    {
        $this->assertOptimize(array(
            array(
                'and_a' => array (
                    'and.f1',
                    'or.f2',
                ),
            ),
        ),$this->builder()
            ->relatedTo('a', function($b) {
                $b->_and('f1', 1);
            })
            ->orRelatedTo('a', function($b) {
                $b->_and('f2', 1);
            })
        );
        
        $this->assertOptimize(array(
            array (
                'and_a' => array (
                    'and.f1',
                ),
            ),
            array (
                '!or_a' => array (
                    'and.f2',
                ),
            ),
        ),$this->builder()
            ->relatedTo('a', function($b) {
                $b->_and('f1', 1);
            })
            ->orNotRelatedTo('a', function($b) {
                $b->_and('f2', 1);
            })
        );
        
        $this->assertOptimize(array(
            array (
                'and_a' => array (
                    'and.f1',
                ),
            ),
            array (
                'xor_a' => array (
                    'and.f2',
                ),
            ),
        ),$this->builder()
            ->relatedTo('a', function($b) {
                $b->_and('f1', 1);
            })
            ->xorRelatedTo('a', function($b) {
                $b->_and('f2', 1);
            })
        );
        
        $this->assertOptimize(array(
            array(
                '!and_a' => array (
                    'and.f1',
                    'and.f2',
                ),
            ),
        ),$this->builder()
            ->notRelatedTo('a', function($b) {
                $b->_and('f1', 1);
            })
            ->orNotRelatedTo('a', function($b) {
                $b->_and('f2', 1);
            })
        );
        
        $this->assertOptimize(array(
            array (
                '!and_a' => array (
                    array (
                        'and' => array (
                            'and.f1',
                            'xor.f2',
                        ),
                    ),
                    'and.f3',
                ),
            ),
        ),$this->builder()
            ->notRelatedTo('a', function($b) {
                $b->_and('f1', 1);
                $b->_xor('f2', 1);
            })
            ->orNotRelatedTo('a', function($b) {
                $b->_and('f3', 1);
            })
        );
        
        $this->assertOptimize(array(
            array(
                '!and_a' => array (
                    'and.f1',
                    'or.f2',
                ),
            ),
        ),$this->builder()
            ->notRelatedTo('a', function($b) {
                $b->_and('f1', 1);
            })
            ->notRelatedTo('a', function($b) {
                $b->_and('f2', 1);
            })
        );
        
        $this->assertOptimize(array(
            array (
                'and_a' => array (
                    'and.f1',
                    'or.f4',
                ),
            ),
            array (
                'or_a' => array (
                    'and.f2',
                ),
            ),
        'and.f3',
        ), $this->builder()
            ->relatedTo('a', function($b) {
                $b->_and('f1', 1);
            })
            ->orRelatedTo('a', function($b) {
                $b->_and('f2', 1);
            })
            ->_and('f3', 1)
            ->orRelatedTo('a', function($b) {
                $b->_and('f4', 1);
            })
        );
        
        $this->assertOptimize(array(
            array (
                'and_a' => array (
                    array (
                        'and_b' => array (
                            'and.f1',
                            'or.f3',
                        ),
                    ),
                ),
            ),
            'or.f2',
            'or.f4',
        ), $this->builder()
            ->relatedTo('a', function($b) {
                $b->relatedTo('b', function($b) {
                    $b->_and('f1', 1);
                });
                
                $b->_or('f2', 1);
            })
            ->orRelatedTo('a', function($b) {
                $b->relatedTo('b', function($b) {
                    $b->_and('f3', 1);
                });
                
                $b->_or('f4', 1);
            })
        );
        
    }

    protected function extractConditions($conds)
    {
        $arr = array();

        foreach ($conds as $cond) {
            $prefix = $cond->logic();
            if ($cond instanceof \PHPixie\ORM\Conditions\Condition\Group\Relationship) {
                $prefix.= $prefix?'_':'';
                $prefix .= $cond->relationship();
            }

            $negated = $cond->negated() ? '!':'';

            if ($cond instanceof \PHPixie\ORM\Conditions\Condition\Group) {
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
        $res = $this->extractConditions($conds);
        var_export($res);

        $this->assertEquals($expected, $res);
    }

    protected function builder()
    {
        return new \PHPixie\ORM\Conditions\Builder\Container($this->conditions);
    }
}
