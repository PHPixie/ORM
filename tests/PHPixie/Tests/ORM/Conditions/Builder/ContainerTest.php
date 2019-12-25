<?php

namespace PHPixie\Tests\ORM\Conditions\Builder;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions\Builder\Container
 */
class ContainerTest extends \PHPixie\Tests\Database\Conditions\Builder\ContainerTest
{
    protected $ormBuilder;
    protected $conditions;
    protected $maps;
    protected $relationshipMap;
    protected $modelName = 'pixie';
    
    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder', array());
        $this->conditions = new \PHPixie\ORM\Conditions($this->ormBuilder);
        $this->maps = $this->quickMock('\PHPixie\ORM\Maps');
        $this->relationshipMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Relationship', array());

        $this->ormBuilder
            ->expects($this->any())
            ->method('maps')
            ->will($this->returnValue($this->maps));
        
        $this->maps
            ->expects($this->any())
            ->method('relationship')
            ->will($this->returnValue($this->relationshipMap));
        
        $self = $this;
        $this->relationshipMap
            ->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($model, $property) use($self) {
                $models = array(
                    'pixie' => 'fairy',
                    'fairy' => 'pixie'
                );
                
                $side = $self->quickMock('\PHPixie\ORM\Relationships\Relationship\Side\Relationship');
                $side
                    ->expects($this->any())
                    ->method('relatedModelName')
                    ->will($this->returnValue($models[$model]));
                
                return $side;
            }));
        
        parent::setUp();
    }
    
    /**
     * @covers ::addOperatorCondition
     * @covers ::startConditionGroup
     * @covers ::<protected>
     */
    public function testSplitRelationship()
    {
        $this->container
                    ->_and('a.b>c.d', 1)
                    ->orNot('a.b.d', 2);
        
        $this->assertConditions(array(
            array('and', false, 'a', array(
                array('and', false, 'b', array(
                    array('and', false, 'c.d', '=', array(1))
                ))
            )),
            array('or', true, 'a', array(
                array('and', false, 'b', array(
                    array('and', false, 'd', '=', array(2))
                ))
            )),
        ));
        
    }
    
    /**
     * @covers ::addOperatorCondition
     * @covers ::startRelatedToConditionGroup
     * @covers ::addRelatedToCondition
     * @covers ::addInCondition
     * @covers ::startConditionGroup
     * @covers ::endGroup
     * @covers ::<protected>
     */
    public function testRelatedConditions()
    {
        $pixieItems = array($this->getInItem('pixie'));
        $fairyItems = array($this->getInItem('fairy'));
    
        $this->container
                    ->relatedTo('a.b', function($container){
                        $container->orNot('c', '>', 1);
                    })
                    ->relatedTo('c', $fairyItems)
                    ->orIn($pixieItems);
        
        $this->assertConditions(array(
            array('and', false, 'a', array(
                array('and', false, 'b', array(
                    array('or', true, 'c', '>', array(1))
                ))
            )),
            array('and', false, 'c', array(
                array('and', false, $fairyItems)
            )),
            array('or', false, $pixieItems)
        ));
        
    }
    
    /**
     * @covers ::buildWhereCondition
     * @covers ::addWhereCondition
     * @covers ::addWhereOperatorCondition
     * @covers ::addWherePlaceholder
     * @covers ::startWhereConditionGroup
     * @covers ::where
     * @covers ::andWhere
     * @covers ::orWhere
     * @covers ::xorWhere
     * @covers ::whereNot
     * @covers ::andWhereNot
     * @covers ::orWhereNot
     * @covers ::xorWhereNot
     * @covers ::startWhereGroup
     * @covers ::startAndWhereGroup
     * @covers ::startOrWhereGroup
     * @covers ::startXorWhereGroup
     * @covers ::startWhereNotGroup
     * @covers ::startAndWhereNotGroup
     * @covers ::startOrWhereNotGroup
     * @covers ::startXorWhereNotGroup
     * @covers ::endWhereGroup
     * @covers ::endGroup
     */
    public function testWhere()
    {
        $expected = array();
        
        $this->container->buildWhereCondition('or', true, array('a', 1));
        $expected[] = array('or', true, 'a', '=', array(1));
        
        $condition = $this->conditions->operator('a', '>', array(2));
        $this->container->addWhereCondition('or', true, $condition);
        $expected[] = array('or', true, 'a', '>', array(2));
        
        $this->container->addWhereOperatorCondition('or', true, 'a', '>', array(1));
        $expected[] = array('or', true, 'a', '>', array(1));
        
        $this->container->startWhereConditionGroup('or', true);
        $this->container->endWhereGroup();
        $expected[] = array('or', true, array());
        
        $this->container->addWherePlaceholder('or', true);
        $expected[] = array('or', true, array());        
        
        foreach(array('and', 'or', 'xor') as $logic) {
            foreach(array(true, false) as $negated) {
                $method = $logic;
                $method.='Where';
                if($negated)
                    $method.='Not';
                
                $this->container->$method('a', 1);
                $expected[] = array($logic, $negated, 'a', '=', array(1));
                
                $method = 'start'.ucfirst($method).'Group';
                $this->container->$method();
                $this->container->endWhereGroup();
                $expected[] = array($logic, $negated, array());
            }
        }
        
        foreach(array(true, false) as $negated) {
            $method = 'where';
            if($negated)
                $method.='Not';
            
            $this->container->$method('a', 1);
            $expected[] = array('and', $negated, 'a', '=', array(1));
            
            $method = 'start'.ucfirst($method).'Group';
            $this->container->$method();
            $this->container->endWhereGroup();
            $expected[] = array('and', $negated, array());
        }
        
        $this->assertConditions($expected);
    }
    
    /**
     * @covers ::addRelatedToCondition
     * @covers ::startRelatedToConditionGroup
     * @covers ::relatedTo
     * @covers ::andRelatedTo
     * @covers ::orRelatedTo
     * @covers ::xorRelatedTo
     * @covers ::notRelatedTo
     * @covers ::andNotRelatedTo
     * @covers ::orNotRelatedTo
     * @covers ::xorNotRelatedTo
     * @covers ::startRelatedToGroup
     * @covers ::startAndRelatedToGroup
     * @covers ::startOrRelatedToGroup
     * @covers ::startXorRelatedToGroup
     * @covers ::startNotRelatedToGroup
     * @covers ::startAndNotRelatedToGroup
     * @covers ::startOrNotRelatedToGroup
     * @covers ::startXorNotRelatedToGroup
     * @covers ::endGroup
     */
    public function testRelatedTo()
    {
        $items = array($this->getInItem('fairy'));
        
        $expected = array();
        
        $this->container->addRelatedToCondition('or', true, 'a', $items);
        $expected[] = array('or', true, 'a', array(
            array('and', false, $items)
        ));
        
        $this->container->addRelatedToCondition('or', true, 'a');
        $expected[] = array('or', true, 'a', array(
            
        ));
        
        $this->container->startRelatedToConditionGroup('a', 'or', true);
        $this->container->endGroup();
        $expected[] = array('or', true, 'a', array());
        
        foreach(array('and', 'or', 'xor') as $logic) {
            foreach(array(true, false) as $negated) {
                $method = $logic;
                if($negated)
                    $method.='Not';
                $method.='RelatedTo';
                
                
                $this->container->$method('a', $items);
                $expected[] = array($logic, $negated, 'a', array(
                    array('and', false, $items)
                ));
                
                $method = 'start'.ucfirst($method).'Group';
                $this->container->$method('a');
                $this->container->endGroup();
                $expected[] = array($logic, $negated, 'a', array());
            }
        }
        
        foreach(array(true, false) as $negated) {
            $method = 'relatedTo';
            if($negated)
                $method = 'not'.ucfirst($method);
            
            $this->container->$method('a', $items);
            $expected[] = array('and', $negated, 'a', array(
                array('and', false, $items)
            ));
            
            $this->container->$method('a', function($c){
                $c->_and('b', 1);
            });
            $expected[] = array('and', $negated, 'a', array(
                array('and', false, 'b', '=', array(1))
            ));
            
            $method = 'start'.ucfirst($method).'Group';
            $this->container->$method('a');
            $this->container->endGroup();
            $expected[] = array('and', $negated, 'a', array());
        }
        
        $this->assertConditions($expected);
    }
    
    /**
     * @covers ::in
     * @covers ::andIn
     * @covers ::orIn
     * @covers ::xorIn
     * @covers ::notIn
     * @covers ::andNotIn
     * @covers ::orNotIn
     * @covers ::xorNotIn
     * @covers ::endGroup
     */
    public function testIn()
    {
        $items = array($this->getInItem('pixie'));
        $expected = array();
        
         
        foreach(array('and', 'or', 'xor') as $logic) {
            foreach(array(true, false) as $negated) {
                $method = $logic;
                if($negated)
                    $method.='Not';
                $method.='In';
                
                $this->container->$method($items);
                $expected[] = array($logic, $negated, $items);
                
            }
        }
        
        foreach(array(true, false) as $negated) {
            $method = 'in';
            if($negated)
                $method = 'not'.ucfirst($method);
            
            $this->container->$method($items);
            $expected[] = array('and', $negated, $items);
            
        }
        
        $this->assertConditions($expected);
    }
    
    protected function assertPlaceholder($container, $logic, $negated, $allowEmpty, $modelName = null)
    {
        parent::assertPlaceholder($container, $logic, $negated, $allowEmpty);
        
        $placeholder = $this->getLastCondition();
        
        if($modelName === null) {
            $modelName = $this->modelName;
        }
        $this->assertAttributeSame($modelName, 'currentModelName', $placeholder->container());
    }
    
    protected function assertCondition($condition, $expected)
    {
        if($condition instanceof \PHPixie\ORM\Conditions\Condition\Collection\RelatedTo\Group) {
            $this->assertEquals($expected[2], $condition->relationship());
            $this->assertConditionArray($condition->conditions(), $expected[3]);
            
        }elseif($condition instanceof \PHPixie\ORM\Conditions\Condition\In){
            $this->assertEquals($expected[2], $condition->items());
            
        }else{
            parent::assertCondition($condition, $expected);
        }
    }
    
    protected function getInItem($modelName)
    {
        $item = $this->quickMock('\PHPixie\ORM\Conditions\Condition\In\Item');
        $item
            ->expects($this->any())
            ->method('modelName')
            ->will($this->returnValue($modelName));
        return $item;
    }
    
    protected function container($defaultOperator = '=')
    {
        return $this->conditions->container(
            $this->modelName,
            $defaultOperator
        );
    }
}