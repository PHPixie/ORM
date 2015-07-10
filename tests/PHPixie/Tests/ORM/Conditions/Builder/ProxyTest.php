<?php

namespace PHPixie\Tests\ORM\Conditions\Builder;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions\Builder\Proxy
 */
class ProxyTest extends \PHPixie\Test\Testcase
{
    protected $builder;
    protected $proxy;
    
    public function setUp()
    {
        $this->builder = $this->getBuilder();
        $this->proxy = $this->proxy();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::addCondition
     * @covers ::buildCondition
     * @covers ::addOperatorCondition
     * @covers ::addPlaceholder
     * @covers ::startConditionGroup
     * @covers ::_and
     * @covers ::_or
     * @covers ::_xor
     * @covers ::_not
     * @covers ::andNot
     * @covers ::orNot
     * @covers ::xorNot
     * @covers ::startGroup
     * @covers ::startAndGroup
     * @covers ::startOrGroup
     * @covers ::startXorGroup
     * @covers ::startNotGroup
     * @covers ::startAndNotGroup
     * @covers ::startOrNotGroup
     * @covers ::startXorNotGroup
     * @covers ::endGroup
     */
    public function testShorthand()
    {
        $this->method($this->builder, 'addCondition', null, array('and', true, 'test'), 0);
        $this->proxy->addCondition('and', true, 'test');
        
        $this->method($this->builder, 'buildCondition', null, array('and', true, array('a', 1)), 0);
        $this->proxy->buildCondition('and', true, array('a', 1));
        
        $this->method($this->builder, 'addOperatorCondition', null, array('or', true, 'a', '>', 1), 0);
        $this->proxy->addOperatorCondition('or', true, 'a', '>', 1);
        
        $this->method($this->builder, 'startConditionGroup', null, array('or', true), 0);
        $this->proxy->startConditionGroup('or', true);
        
        $this->method($this->builder, 'endGroup', null, array(), 0);
        $this->proxy->endGroup();
        
        $this->method($this->builder, 'addPlaceholder', null, array('or', true), 0);
        $this->proxy->addPlaceholder('or', true);
        
        foreach(array('and', 'or', 'xor') as $logic) {
            foreach(array(true, false) as $negated) {
                $method = $logic;
                if($negated) {
                    $method.='Not';
                }
                
                $shortMethod = $method;
                if(!$negated) {
                    $shortMethod = '_'.$method;
                }
                
                $this->method($this->builder, 'buildCondition', null, array($logic, $negated, array('a', 1)), 0);
                $this->proxy->$shortMethod('a', 1);
                
                $method = 'start'.ucfirst($method).'Group';
                $this->method($this->builder, 'startConditionGroup', null, array($logic, $negated), 0);
                $this->proxy->$method();
            }
        }
        
        $this->method($this->builder, 'buildCondition', null, array('and', true, array('a', 1)), 0);
        $this->proxy->_not('a', 1);
        
        foreach(array(true, false) as $negate) {
            $method = 'start';
            
            if($negate)
                $method.='Not';
            
            $method.='Group';
            
            $this->method($this->builder, 'startConditionGroup', null, array('and', $negate), 0);
            $this->proxy->$method();
        }

    }
    
    /**
     * @covers ::addWhereCondition
     * @covers ::buildWhereCondition
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
        $this->method($this->builder, 'addWhereCondition', null, array('or', true, 'test'), 0);
        $this->proxy->addWhereCondition('or', true, 'test');
        
        $this->method($this->builder, 'buildWhereCondition', null, array('and', true, array('a', 1)), 0);
        $this->proxy->buildWhereCondition('and', true, array('a', 1));
        
        $this->method($this->builder, 'addWhereOperatorCondition', null, array('or', true, 'a', '>', 1), 0);
        $this->proxy->addWhereOperatorCondition('or', true, 'a', '>', 1);
        
        $this->method($this->builder, 'startWhereConditionGroup', null, array('or', true), 0);
        $this->proxy->startWhereConditionGroup('or', true);
        
        $this->method($this->builder, 'endWhereGroup', null, array(), 0);
        $this->proxy->endWhereGroup();
        
        $this->method($this->builder, 'addWherePlaceholder', null, array('or', true), 0);
        $this->proxy->addWherePlaceholder('or', true);
        
        foreach(array('and', 'or', 'xor') as $logic) {
            foreach(array(true, false) as $negated) {
                $method = $logic;
                $method.='Where';
                if($negated)
                    $method.='Not';
                
                $this->method($this->builder, 'buildCondition', null, array($logic, $negated, array('a', 1)), 0);
                $this->proxy->$method('a', 1);
                
                $method = 'start'.ucfirst($method).'Group';
                $this->method($this->builder, 'startWhereConditionGroup', null, array($logic, $negated), 0);
                $this->proxy->$method();
            }
        }
        
        foreach(array(true, false) as $negated) {
            $method = 'where';
            if($negated)
                $method.='Not';
            
            $this->method($this->builder, 'buildCondition', null, array('and', $negated, array('a', 1)), 0);
            $this->proxy->$method('a', 1);
            
            $this->method($this->builder, 'startWhereConditionGroup', null, array('and', $negated), 0);
            $method = 'start'.ucfirst($method).'Group';
            $this->proxy->$method();
        }
        
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
        $items = array('items');
        
        $this->method($this->builder, 'addRelatedToCondition', null, array('or', true, 'a', $items), 0);
        $this->proxy->addRelatedToCondition('or', true, 'a', $items);
        
        $this->method($this->builder, 'addRelatedToCondition', null, array('or', true, 'a', null), 0);
        $this->proxy->addRelatedToCondition('or', true, 'a');
        
        $this->method($this->builder, 'startRelatedToConditionGroup', null, array('a', 'or', true), 0);
        $this->proxy->startRelatedToConditionGroup('a', 'or', true);
        
        foreach(array('and', 'or', 'xor') as $logic) {
            foreach(array(true, false) as $negated) {
                $method = $logic;
                if($negated)
                    $method.='Not';
                $method.='RelatedTo';
                
                $this->method($this->builder, 'addRelatedToCondition', null, array($logic, $negated, 'a', $items), 0);
                $this->proxy->$method('a', $items);
                
                $this->method($this->builder, 'addRelatedToCondition', null, array($logic, $negated, 'a', null), 0);
                $this->proxy->$method('a');
                
                $method = 'start'.ucfirst($method).'Group';
                $this->method($this->builder, 'startRelatedToConditionGroup', null, array('a', $logic, $negated), 0);
                $this->proxy->$method('a');
            }
        }
        
        foreach(array(true, false) as $negated) {
            $method = 'relatedTo';
            if($negated)
                $method = 'not'.ucfirst($method);
            
            $this->method($this->builder, 'addRelatedToCondition', null, array('and', $negated, 'a', $items), 0);
            $this->proxy->$method('a', $items);
            
            $method = 'start'.ucfirst($method).'Group';
            $this->method($this->builder, 'startRelatedToConditionGroup', null, array('a', 'and', $negated), 0);
            $this->proxy->$method('a');
        }
        
    }
    
    /**
     * @covers ::addInCondition
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
        
        $this->method($this->builder, 'addInCondition', null, array('and', true, $items, 'a'), 0);
        $this->proxy->addInCondition('and', true, $items, 'a');
        
        foreach(array('and', 'or', 'xor') as $logic) {
            foreach(array(true, false) as $negated) {
                $method = $logic;
                if($negated)
                    $method.='Not';
                $method.='In';
                
                $this->method($this->builder, 'addInCondition', null, array($logic, $negated, $items, null), 0);
                $this->proxy->$method($items);
            }
        }
        
        foreach(array(true, false) as $negated) {
            $method = 'in';
            if($negated)
                $method = 'not'.ucfirst($method);
            
            $this->method($this->builder, 'addInCondition', null, array('and', $negated, $items, null), 0);
            $this->proxy->$method($items);
            
        }
    }
    
    /**
     * @covers ::__call
     * @covers ::<protected>
     */
    public function testCall()
    {
        $this->callTest();
    }
    
    protected function callTest()
    {
        foreach(array('and', 'or', 'xor') as $logic) {
            $this->method($this->builder, 'buildCondition', null, array($logic, false, array('a', 1)), 0);
            $this->proxy->$logic('a', 1);
        }
        
        $this->method($this->builder, 'buildCondition', null, array('and', true, array('a', 1)), 0);
        $this->proxy->not('a', 1);
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Builder');
        $this->proxy->maybe('a', 1);
    }
    
    protected function getBuilder()
    {
        return $this->abstractMock('\PHPixie\ORM\Conditions\Builder');
    }
    
    protected function proxy()
    {
        return new \PHPixie\ORM\Conditions\Builder\Proxy($this->builder);
    }
    
}