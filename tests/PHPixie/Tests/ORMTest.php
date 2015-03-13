<?php

namespace PHPixie\Tests;

class ORMStub extends \PHPixie\ORM
{
    protected $builderMock;
    
    public function __construct($builderMock, $database, $configSlice, $wrappers)
    {
        $this->builderMock = $builderMock;
        parent::__construct($database, $configSlice, $wrappers);
    }
    
    protected function buildBuilder($database, $configSlice, $wrappers)
    {
        return $this->builderMock;
    }
}

/**
 * @coversDefaultClass \PHPixie\ORM
 */
class ORMTest extends \PHPixie\Test\Testcase {
    protected $database;
    protected $configSlice;
    protected $wrappers;
    
    protected $orm;
    
    protected $builder;
    protected $models;
    protected $databaseModel;
    
    public function setUp()
    {
        $this->database = $this->quickMock('\PHPixie\Database');
        $this->configSlice = $this->abstractMock('\PHPixie\Slice\Data');
        $this->wrappers = $this->abstractMock('\PHPixie\ORM\Wrappers');
        
        $this->builder = $this->quickMock('\PHPixie\ORM\Builder');
        $this->orm = new ORMStub(
            $this->builder,
            $this->database,
            $this->configSlice,
            $this->wrappers
        );
        
        $this->models = $this->quickMock('\PHPixie\ORM\Models');
        $this->databaseModel = $this->quickMock('\PHPixie\ORM\Models\Type\Database');
        
        $this->method($this->builder, 'models', $this->models, array());
        $this->method($this->models, 'database', $this->databaseModel, array());
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::repository
     * @covers ::<protected>
     */
    public function testRepository()
    {
        $this->databaseProxyTest('repository');
    }

    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $this->databaseProxyTest('query');
    }
    
    /**
     * @covers ::createEntity
     * @covers ::<protected>
     */
    public function testCreateEntity()
    {
        $entity = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Entity');
        $repository = $this->prepareDatabaseProxy('repository', 'pixie', null);
        
        $this->method($repository, 'create', $entity, array(null), 0);
        $this->assertSame($entity, $this->orm->createEntity('pixie'));
        
        $data = (object) array('a' => 1);
        $this->method($repository, 'create', $entity, array($data), 0);
        $this->assertSame($entity, $this->orm->createEntity('pixie', $data));
    }
    
    /**
     * @covers ::repositories
     * @covers ::<protected>
     */
    public function testRepositories()
    {
        $repositories = $this->quickMock('\PHPixie\ORM\Repositories');
        $this->method($this->builder, 'repositories', $repositories, array(), 0);
        $this->assertSame($repositories, $this->orm->repositories());
    }
    
    /**
     * @covers ::builder
     * @covers ::<protected>
     */
    public function testBuilder()
    {
        $this->assertSame($this->builder, $this->orm->builder());
    }
    
    /**
     * @covers ::buildBuilder
     * @covers ::<protected>
     */
    public function testBuilderInstance()
    {
        $this->orm = new \PHPixie\ORM(
            $this->database,
            $this->configSlice,
            $this->wrappers  
        );
        
        $builder = $this->orm->builder();
        $this->assertInstance($builder, '\PHPixie\ORM\Builder', array(
            'database'    => $this->database,
            'configSlice' => $this->configSlice,
            'wrappers'    => $this->wrappers,
        ));
    }

    protected function databaseProxyTest($type)
    {
        $instance = $this->prepareDatabaseProxy($type, 'pixie');
        $this->assertSame($instance, $this->orm->$type('pixie'));
    }
    
    protected function prepareDatabaseProxy($type, $modelName = 'pixie', $at = 0)
    {
        $instance = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\\'.ucfirst($type));
        $this->method($this->databaseModel, $type, $instance, array($modelName), $at);
        return $instance;
    }
    
}