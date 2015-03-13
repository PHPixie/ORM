<?php

namespace PHPixie\Tests\ORM\Mappers\Con;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Conditions\Optimizer
 */
class OptimizerTest extends \PHPixie\Test\Testcase
{
    protected $ormBuilder;
    protected $maps;
    protected $relationshipMap;
    
    protected $mappers;
    protected $conditions;
    
    protected $optimizer;
    
    protected $normalizer;
    protected $modelName = 'pixie';

    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
        $this->maps = $this->quickMock('\PHPixie\ORM\Maps');
        $this->relationshipMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Relationship');
        
        $this->method($this->ormBuilder, 'maps', $this->maps, array());
        $this->method($this->maps, 'relationship', $this->relationshipMap, array());
        
        $self = $this;
        $this->method($this->relationshipMap, 'get', function($model, $property) use($self) {
            $models = array(
                'pixie' => 'fairy',
                'fairy' => 'pixie'
            );

            $side = $self->quickMock('\PHPixie\ORM\Relationships\Relationship\Side\Relationship');
            $self->method($side, 'relatedModelName', $models[$model], array());

            return $side;
        });
        
        $this->conditions = new \PHPixie\ORM\Conditions($this->ormBuilder);
        $this->mappers = $this->quickMock('\PHPixie\ORM\Mappers');

        $this->optimizer = new \PHPixie\ORM\Mappers\Conditions\Optimizer($this->mappers, $this->conditions);
        
        $this->normalizer = $this->quickMock('\PHPixie\ORM\Mappers\Conditions\Normalizer');
        $this->method($this->mappers, 'conditionsNormalizer', $this->normalizer, array());
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
            'and.f1',
            array(
                'and' => array(
                    'and.f2',
                    'or.f3',
                )
            )
        ),$this->builder()
            ->_and('f1', 1)
            ->_and(function($b) {
                $b
                    ->_and('f2', 1)
                    ->_or('f3', 1);
            })
        );
        
        $this->assertOptimize(array(
            'and.f1',
        ),$this->builder()
            ->_and('f1', 1)
            ->_and(function($b) {

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
                    'and.f1'
                ),
            ), array(
                '!or_a' => array (
                    'and.f2'
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
                    'and.f1',
                    'xor.f2',
                ),
            ),
            array (
                '!or_a' => array (
                    'and.f3',
                ),
            )
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
                    'or.f2'
                ),
            )
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
                    'or.f2',
                    'or.f4',
                ),
            )
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
        
        $this->assertOptimize(array(
            'or.b',
            'or.f2',
            'or.f1',
        ), $this->builder()
            ->_or(function($b) {
                $b->_and('b', function($b) {
                    $b->_and('f1', 1);
                });
                
                $b->_or('f2', 1);
            })
            ->_or(function($b) {
                $b->_and(function($b) {
                    $b->_and('f1', 1);
                });
            })
        );
        
        
        $this->assertOptimize(array(
            array (
                '!and_a' => array (
                    array (
                        'and_b' => array (
                            'and.f1',
                            'or.f3'
                        ),
                    ),
                    'or.f2',
                    'or.f4',
                ),
            )
        ), $this->builder()
            ->notRelatedTo('a', function($b) {
                $b->relatedTo('b', function($b) {
                    $b->_and('f1', 1);
                });
                
                $b->_or('f2', 1);
            })
            ->notRelatedTo('a', function($b) {
                $b->relatedTo('b', function($b) {
                    $b->_and('f3', 1);
                });
                
                $b->_or('f4', 1);
            })
        );
        
        
    }
    
    /**
     * @covers ::optimize
     * @covers ::<protected>
     */
    public function testOptimizeIn()
    {
        $inCondition = $this->conditions->in('pixie');
        
        $group = $this->conditions->container('pixie')
                    ->startConditionGroup('or', true)
                        ->_and('f1', 1)
                    ->endGroup()
                    ->getConditions();
        
        $group = current($group);
                    
        $this->method($this->normalizer, 'normalizeIn', $group, array($inCondition), 0);
        $this->method($this->normalizer, 'normalizeIn', $group, array($inCondition), 1);
            
        $this->assertOptimize(array(
            array(
                '!or' => array(
                    'and.f1',
                )
            )
        ), $this->builder()
            ->addCondition('or', true,  $inCondition)
        );
    }

    protected function extractConditions($conds)
    {
        $arr = array();

        foreach ($conds as $cond) {
            $prefix = $cond->logic();
            if ($cond instanceof \PHPixie\ORM\Conditions\Condition\Collection\RelatedTo) {
                $prefix.= $prefix?'_':'';
                $prefix .= $cond->relationship();
            }

            $negated = $cond->isNegated() ? '!':'';

            if ($cond instanceof \PHPixie\ORM\Conditions\Condition\Collection) {
                $arr[] = array(
                    $negated.$prefix => $this->extractConditions($cond->conditions())
                );
            } elseif ($cond instanceof \PHPixie\ORM\Conditions\Condition\In) {
                $arr[] = array(
                    $negated.$prefix => 'in'
                );
            } else {
                $arr[] = $prefix.'.'.$negated.$cond->field();
            }
        }

        return($arr);
    }

    protected function assertOptimize($expected, $builder)
    {
        //$conds = $builder->getConditions();
        //$res = $this->extractConditions($conds);
        //var_export($res);

        $conds = $builder->getConditions();
        $res = $this->extractConditions($conds);
        
        for($i=0; $i<2; $i++) {
            $optimized = $this->optimizer->optimize($conds);
            $res = $this->extractConditions($optimized);
            $this->assertEquals($expected, $res);
        }
        
    }

    protected function builder()
    {
        return $this->conditions->container($this->modelName);
    }
}
