<?php

namespace PHPixieTests\ORM\Models;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Model
 */
abstract class ModelTest extends \PHPixieTests\AbstractORMTest
{
    protected $models;
    protected $relationships;
    protected $config;
    
    protected $model;

    protected $inflector;
    protected $relationshipMap;
    protected $wrapper;
    
    protected $type;
    
    public function setUp()
    {
        $this->models = $this->quickMock('\PHPixie\ORM\Models');
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        $this->config = $this->quickMock('\PHPixie\ORM\Config');
        
        $this->inflector = $this->quickMock('\PHPixie\ORM\Configs\Inflector');
        $this->method($this->config, 'inflector', $this->inflector, array());
        
        $this->relationshipMap = $this->quickMock('\PHPixie\ORM\Relationships\Map');
        $this->method($this->relationships, 'map', $this->relationshipMap, array());
        
        $this->wrapper = $this->abstractMock('\PHPixie\ORM\Wrapper');
        $this->method($this->models, 'wrapper', $this->wrapper, array());
        $this->prepareWrapper();
        
        $this->model = $this->model();
    }
    
    /**
     * @covers \PHPixie\ORM\Models\Model::__construct
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::config
     * @covers ::<protected>
     */
    public function testConfig()
    {
        $config = $this->prepareConfig('fairy');
        $this->assertSame($config, $this->model->config('fairy'));
        $this->assertSame($config, $this->model->config('fairy'));
    }
    
    /**
     * @covers ::type
     * @covers ::<protected>
     */
    public function testType()
    {
        $this->assertSame($this->type, $this->model->type());
    }
    
    protected function prepareConfig($modelName)
    {
        $configSlice = $this->getConfigSlice();
        $this->method($this->models, 'modelConfigSlice', $configSlice, array('fairy'), 0);
        $this->method($configSlice, 'get', $this->type, array('type', 'database'), 0);
        return $this->prepareBuildConfig($modelName, $configSlice);
    }
    
    protected function getConfigSlice()
    {
        return $this->abstractMock('\PHPixie\Config\Slice');
    }
    
    protected abstract function prepareBuildConfig($modelName, $configSlice);
    protected abstract function prepareWrapper();
    protected abstract function model();
}