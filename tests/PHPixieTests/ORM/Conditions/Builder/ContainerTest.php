<?php

namespace PHPixieTests\ORM\Conditions\Builder;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions\Builder\Container
 */
class ContainerTest extends \PHPixieTests\Database\Conditions\Builder\ContainerTest
{
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
     * @covers ::<protected>
     */
    public function testRelatedConditions()
    {
        $items = array('items');
    
        $this->container
                    ->relatedTo('a.b', function($container){
                        $container->orNot('c', '>', 1);
                    })
                    ->relatedTo('c', $items)
                    ->orIn($items);
        
        $this->assertConditions(array(
            array('and', false, 'a', array(
                array('and', false, 'b', array(
                    array('or', true, 'c', '>', array(1))
                ))
            )),
            array('and', false, 'c', array(
                array('and', false, $items)
            )),
            array('or', false, $items)
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
     */
    public function testRelatedTo()
    {
        $items = array('items');
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
     */
    public function testIn()
    {
        $items = array('items');
        $expected = array();
        
         
        foreach(array('and', 'or', 'xor') as $logic) {
            foreach(array(true, false) as $negated) {
                $method = $logic;
                if($negated)
                    $method.='Not';
                $method.='In';
                
                $this->container->$method('a', $items);
                $expected[] = array($logic, $negated, 'a', array(
                    array('and', false, $items)
                ));
                
            }
        }
        
        foreach(array(true, false) as $negated) {
            $method = 'in';
            if($negated)
                $method = 'not'.ucfirst($method);
            
            $this->container->$method('a', $items);
            $expected[] = array('and', $negated, 'a', array(
                array('and', false, $items)
            ));
            
        }
        
        $this->assertConditions($expected);
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
    
    protected function conditions()
    {
        return new \PHPixie\ORM\Conditions;
    }
    
    protected function container($defaultOperator = '=')
    {
        return new \PHPixie\ORM\Conditions\Builder\Container($this->conditions, $defaultOperator);
    }
}