<?php

namespace PHPixieTests\ORM\Models\Type\Database\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Type\Database\Implementation\Query
 */
abstract class QueryTest extends \PHPixieTests\AbstractORMTest
{
    /**
     * @covers ::limit
     * @covers ::getLimit
     * @covers ::clearLimit
     * @covers ::<protected>
     */
    public function testLimit()
    {
        $this->assertSame(null, $this->query->getLimit());
        
        $this->assertSame($this->query, $this->query->limit(10));
        $this->assertSame(10, $this->query->getLimit());
        
        $this->assertSame($this->query, $this->query->clearLimit());
        $this->assertSame(null, $this->query->getLimit());
        
        $this->invalidArgumentsTest(array($this->query, 'limit'), array(
            array('test'),
            array(null)
        ));
    }
    
    /**
     * @covers ::offset
     * @covers ::getOffset
     * @covers ::clearOffset
     * @covers ::<protected>
     */
    public function testOffset()
    {
        $this->assertSame(null, $this->query->getOffset());
        
        $this->assertSame($this->query, $this->query->offset(10));
        $this->assertSame(10, $this->query->getOffset());
        
        $this->assertSame($this->query, $this->query->clearOffset());
        $this->assertSame(null, $this->query->getOffset());
        
        $this->invalidArgumentsTest(array($this->query, 'offset'), array(
            array('test'),
            array(null)
        ));
    }
    
    /**
     * @covers ::orderAscendingBy
     * @covers ::orderDescendingBy
     * @covers ::getOrderBy
     * @covers ::clearOrderBy
     * @covers ::<protected>
     */
    public function testOrderBy()
    {
        $this->assertSame(array(), $this->query->getOrderBy());
        
        $expected = array(
            $this->addOrderBy('test', 'asc'),
            $this->addOrderBy('pixie', 'asc'),
            $this->addOrderBy('trixie', 'desc'),
            $this->addOrderBy('test', 'desc'),
        );
        
        $this->assertEquals($expected, $this->query->getOrderBy());
        
        $this->assertSame($this->query, $this->clearOrderBy());
        $this->assertSame(array(), $this->query->getOrderBy());
    }
    
    /**
     * @covers ::planFind
     * @covers ::<protected>
     */
    public function testPlanFind()
    {
        $plan = $this->preparePlanFind();
        $this->assertSame($plan, $this->query->planDelete());
        
        $plan = $this->preparePlanFind(array('test'));
            $this->assertSame($plan, $this->query->planFind(array('test')));
    }
    
    /**
     * @covers ::find
     * @covers ::<protected>
     */
    public function testFind()
    {
        $loader = $this->getLoader();
        
        $plan = $this->preparePlanFind();
        $this->method($plan, 'execute', $loader, array(), 0);
        $this->assertSame($loader, $this->query->find());
        
        $plan = $this->preparePlanFind(array('test'));
        $this->method($plan, 'execute', $loader, array(), 0);
        $this->assertSame($loader, $this->query->find(array('test')));
    }
    
    /**
     * @covers ::findOne
     * @covers ::<protected>
     */
    public function testFindOne()
    {
        $this->findOneTest(null, true, false);
        $this->findOneTest(5, false, true);
    }
    
    protected function findOneTest($limit = null, $exists = true, $preload = false)
    {
        $this->method($this->query, 'getLimit', $limit, array(0), 0);
        $this->method($this->query, 'limit', null, array(1), 1);
        
        $loader = $this->getLoader();
        
        $preloadParams = $preload ? array() : array('test');
        $plan = $this->preparePlanFind($preloadParams);
        
        $this->method($plan, 'execute', $loader, array(), 0);
        $this->method($loader, 'offsetExists', $exists, array(0), 0);
        
        $entity = null;
        if($exists) {
            $entity = $this->getEntity();
            $this->method($loader, 'getByOffset', $entity, array(0), 1);
        }
        
        if($limit === null) {
            $this->method($this->query, 'clearLimit', null, array(), 2);
        }else{
            $this->method($this->query, 'limit', null, array($limit), 2);
        }
        
        if($preload)
        {
            $res = $this->query->findOne($preload);
        }else{
            $res = $this->query->findOne();
        }
        
        $this->assertSame($entity, $res);
    }
    
    /**
     * @covers ::planUpdate
     * @covers ::<protected>
     */
    public function testPlanUpdate()
    {
        $data = array('name' => 'Pixie');
        $plan = $this->preparePlanDelete($data);
        $this->assertSame($plan, $this->query->planUpdate($data));
    }
    
    /**
     * @covers ::update
     * @covers ::<protected>
     */
    public function testUpdate()
    {
        $data = array('name' => 'Pixie');
        $plan = $this->preparePlanDelete($data);
        $this->method($plan, 'execute', $loader, array(), 0);
        $this->query->update($data)
    }
    
    /**
     * @covers ::planDelete
     * @covers ::<protected>
     */
    public function testPlanDelete()
    {
        $plan = $this->preparePlanDelete();
        $this->assertSame($plan, $this->query->planDelete());
    }
    
    /**
     * @covers ::delete
     * @covers ::<protected>
     */
    public function testDelete()
    {
        $plan = $this->preparePlanDelete();
        $this->method($plan, 'execute', null, array(), 0);
        $this->query->delete();
    }
    
    
    protected function invalidArgumentsTest($callback, $paramSets)
    {
        foreach($paramSets as $params) {
            $except = false;
            try {
                call_user_func_array($callback, $params);
            } catch('\PHPixie\ORM\Exception\Query') {
                $except = true;
            }
            $this->assertSame(true, $except);
        }
    }
    
    protected function addOrderBy($field, $dir)
    {
        $orderBy = $this->quickMock('\PHPixie\ORM\Values\OrderBy', array());
        $this->method($this->values, 'orderBy', $orderBy, array($field, $dir), 0);
        
        if($dir === 'asc') {
            $query = $this->query->addOrderAscendingBy($field);
        }else{
            $query = $this->query->addOrderDescendingBy($field);
        }
        
        $this->assertSame($this->query, $query);
        return $orderBy;
    }
                              
    protected function preparePlanFind($preload = array())
    {
        $plan = $this->getPlan();
        $this->method($this->mapper, 'mapFind', $plan, array($preload), 0);
        return $plan;
    }
    
    protected function preparePlanUpdate($data)
    {
        $plan = $this->getPlan();
        $this->method($this->mapper, 'mapUpdate', $plan, array($data), 0);
        return $plan;
    }
    
    protected function preparePlanDelete()
    {
        $plan = $this->getPlan();
        $this->method($this->mapper, 'mapDelete', $plan, array(), 0);
        return $plan;
    }
    
    
    
    protected function getPlan()
    {
        return $this->abstractMock('\PHPixie\ORM\Plans\Plan');
    }
    
    protected function getLoader()
    {
        return $this->abstractMock('\PHPixie\ORM\Loaders\Loader');
    }
}