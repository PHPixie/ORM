<?php

namespace PHPixie\Tests\ORM\Models\Type\Database\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Type\Database\Implementation\Query
 */
abstract class QueryTest extends \PHPixie\Tests\ORM\Conditions\Builder\ProxyTest
{
    protected $values;
    protected $queryPropertyMapper;
    protected $queryPropertyMap;
    protected $config;
    
    protected $configData = array(
        'model' => 'fairies'
    );
    
    protected $container;
    protected $query;
    
    public function setUp()
    {
        $this->values = $this->quickMock('\PHPixie\ORM\Values');
        $this->queryMapper = $this->quickMock('\PHPixie\ORM\Mappers\Query');
        $this->queryPropertyMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Property\Query');
        $this->config = $this->config();
        
        parent::setUp();
        
        $this->container = $this->builder;
        $this->query = $this->proxy;
    }
    
    /**
     * @covers \PHPixie\ORM\Conditions\Builder\Proxy::__construct
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::modelName
     * @covers ::<protected>
     */
    public function testModelName()
    {
        $this->assertSame($this->configData['model'], $this->query->modelName());
    }
    
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
        
        $this->assertSame($this->query, $this->query->clearOrderBy());
        $this->assertSame(array(), $this->query->getOrderBy());
    }
    
    /**
     * @covers ::getConditions
     * @covers ::<protected>
     */
    public function testGetConditions()
    {
        $conditions = array('test');
        $this->method($this->container, 'getConditions', $conditions, array(), 0);
        $this->assertSame($conditions, $this->query->getConditions());
    }
    
    
    /**
     * @covers ::planFind
     * @covers ::<protected>
     */
    public function testPlanFind()
    {
        $plan = $this->preparePlanFind();
        $this->assertSame($plan, $this->query->planFind());
        
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
        $queryMock = $this->queryMock(array('limit', 'getLimit', 'clearLimit'));
        $this->findOneTest($queryMock, null, true, false);
        $this->findOneTest($queryMock, 5, false, true);
    }
    
    protected function findOneTest($queryMock, $limit = null, $exists = true, $preload = false)
    {
        $this->method($queryMock, 'getLimit', $limit, array(), 0);
        $this->method($queryMock, 'limit', null, array(1), 1);
        
        $loader = $this->getLoader();
        
        $preloadParams = $preload ? array('test') : array();
        $plan = $this->preparePlanFind($preloadParams, $queryMock);
        
        $this->method($plan, 'execute', $loader, array(), 0);
        $this->method($loader, 'offsetExists', $exists, array(0), 0);
        
        $entity = null;
        if($exists) {
            $entity = $this->getEntity();
            $this->method($loader, 'getByOffset', $entity, array(0), 1);
        }
        
        if($limit === null) {
            $this->method($queryMock, 'clearLimit', null, array(), 2);
        }else{
            $this->method($queryMock, 'limit', null, array($limit), 2);
        }
        
        if($preload)
        {
            $res = $queryMock->findOne($preloadParams);
        }else{
            $res = $queryMock->findOne();
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
        $plan = $this->preparePlanUpdate($data);
        $this->assertSame($plan, $this->query->planUpdate($data));
    }
    
    /**
     * @covers ::update
     * @covers ::<protected>
     */
    public function testUpdate()
    {
        $data = array('name' => 'Pixie');
        $plan = $this->preparePlanUpdate($data);
        $this->method($plan, 'execute', null, array(), 0);
        $this->assertSame($this->query, $this->query->update($data));
    }
    
    /**
     * @covers ::getUpdateBuilder
     * @covers ::<protected>
     */
    public function testGetUpdateBuilder()
    {
        $update = $this->quickMock('\PHPixie\ORM\Values\Update\Builder');
        $this->method($this->values, 'updateBuilder', $update, array($this->query), 0);
        $this->assertSame($update, $this->query->getUpdateBuilder());
    }
    
    /**
     * @covers ::planUpdateValue
     * @covers ::<protected>
     */
    public function testPlanUpdateValue()
    {
        $update = $this->getUpdate();
        $plan = $this->preparePlanUpdateValue($update);
        $this->assertSame($plan, $this->query->planUpdateValue($update));
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
        $this->assertSame($this->query, $this->query->delete());
    }
    
    /**
     * @covers ::planCount
     * @covers ::<protected>
     */
    public function testPlanCount()
    {
        $plan = $this->preparePlanCount();
        $this->assertSame($plan, $this->query->planCount());
    }
    
    /**
     * @covers ::count
     * @covers ::<protected>
     */
    public function testCount()
    {
        $plan = $this->preparePlanCount();
        $this->method($plan, 'execute', 5, array(), 0);
        $this->assertSame(5, $this->query->count());
    }
    
    
    protected function invalidArgumentsTest($callback, $paramSets)
    {
        foreach($paramSets as $params) {
            $except = false;
            try {
                call_user_func_array($callback, $params);
            } catch(\PHPixie\ORM\Exception\Query $e) {
                $except = true;
            }
            $this->assertSame(true, $except);
        }
    }
    
    protected function addOrderBy($field, $dir)
    {
        $orderBy = $this->quickMock('\PHPixie\ORM\Values\OrderBy', array());
        $this->method($this->values, 'orderBy', $orderBy, array($field, $dir), 0);
        
        $query = null;
        
        if($dir === 'asc') {
            $query = $this->query->orderAscendingBy($field);
        }else{
            $query = $this->query->orderDescendingBy($field);
        }
        
        $this->assertSame($this->query, $query);
        return $orderBy;
    }
                              
    protected function preparePlanFind($preloadPaths = array(), $query = null)
    {
        if($query === null)
            $query = $this->query;
        
        $preload = $this->quickMock('\PHPixie\ORM\Values\Preload');
        $this->method($this->values, 'preload', $preload, array(), 0);
        foreach($preloadPaths as $key => $path) {
            $this->method($preload, 'add', null, array($path), $key);
        }
        
        $plan = $this->getPlan();
        $this->method($this->queryMapper, 'mapFind', $plan, array($query, $preload), 0);
        return $plan;
    }
    
    protected function preparePlanUpdate($data)
    {
        
        $update = $this->getUpdate();
        $this->method($this->values, 'update', $update, array($this->query), 0);
        $key = 0;
        foreach($data as $field => $value) {
            $this->method($update, 'set', null, array($field, $value), $key);
            $key++;
        }
        
        return $this->preparePlanUpdateValue($update);
    }
    
    protected function preparePlanUpdateValue($update)
    {
        $plan = $this->getPlan();
        $this->method($this->queryMapper, 'mapUpdate', $plan, array($this->query, $update), 0);
        return $plan;
    }
    
    protected function preparePlanDelete()
    {
        $plan = $this->getPlan();
        $this->method($this->queryMapper, 'mapDelete', $plan, array(), 0);
        return $plan;
    }
    
    protected function preparePlanCount()
    {
        $plan = $this->getPlan();
        $this->method($this->queryMapper, 'mapCount', $plan, array(), 0);
        return $plan;
    }
    
    /**
     * @covers ::getRelationshipProperty
     * @covers ::<protected>
     */
    public function testGetRelationshipProperty()
    {
        $this->prepareRequirePropertyNames(array('test'));
        $property = $this->prepareProperty('test', 1);
        for($i=0; $i<2; $i++) {
            $this->assertSame($property, $this->query->getRelationshipProperty('test'));
        }
        
        $query = $this->query();
        $this->assertException(function() use($query) {
            $this->query->getRelationshipProperty('trixie');
        }, '\PHPixie\ORM\Exception\Relationship');
    }
    
    /**
     * @covers ::__get
     * @covers ::<protected>
     */
    public function testGet()
    {
        $this->prepareRequirePropertyNames(array('test'));
        $property = $this->prepareProperty('test', 1);
        for($i=0; $i<2; $i++) {
            $this->assertSame($property, $this->query->test);
        }
    }
    
    /**
     * @covers ::__call
     * @covers ::<protected>
     */
    public function testCall()
    {
        $this->prepareRequirePropertyNames(array());
        $this->callTest();
    }
    
    /**
     * @covers ::__call
     * @covers ::<protected>
     */
    public function testInvokePropert()
    {
        $this->prepareRequirePropertyNames(array('test'));
        $property = $this->prepareProperty('test', 1);
        for($i=0; $i<2; $i++) {
            $this->method($property, '__invoke', 'trixie', array(), 0);
            $this->assertSame('trixie', $this->query->test());
        }
    }
    
    protected function prepareRequirePropertyNames($names, $at = 0)
    {
        $this->method($this->queryPropertyMap, 'getPropertyNames', $names, array($this->configData['model']), $at);
    }
    
    protected function prepareProperty($name, $at = 0)
    {
        $property = $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Property\Query');
        $this->method($this->queryPropertyMap, 'property', $property, array($this->query, $name), $at, true);
        return $property;
    }
    
    protected function config()
    {
        $config = $this->getConfig();
        foreach($this->configData as $key => $value) {
            $config->$key = $value;
        }
        return $config;
    }
    
    protected function getPlan()
    {
        return $this->abstractMock('\PHPixie\ORM\Plans\Plan');
    }
    
    protected function getLoader()
    {
        return $this->abstractMock('\PHPixie\ORM\Loaders\Loader');
    }
    
    protected function proxy()
    {
        return $this->query();
    }
    
    protected function getBuilder()
    {
        return $this->quickMock('\PHPixie\ORM\Conditions\Builder\Container');
    }
    
    protected function getUpdate()
    {
        return $this->quickMock('\PHPixie\ORM\Values\Update');
    }
    
    abstract protected function getConfig();
    abstract protected function query();
    abstract protected function queryMock($methods);
    abstract protected function getEntity();
}