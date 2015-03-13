<?php

namespace PHPixie\Tests\ORM\Models;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Model
 */
abstract class ModelTest extends \PHPixie\Test\Testcase
{
    protected $models;
    protected $configs;
    
    protected $model;

    protected $mapMocks = array();
    
    protected $inflector;
    protected $wrappers;
    
    protected $type;
    
    public function setUp()
    {
        $this->models = $this->quickMock('\PHPixie\ORM\Models');
        $this->configs = $this->quickMock('\PHPixie\ORM\Configs');
        
        $this->inflector = $this->quickMock('\PHPixie\ORM\Configs\Inflector');
        $this->method($this->configs, 'inflector', $this->inflector, array());
        
        $this->wrappers = $this->abstractMock('\PHPixie\ORM\Wrappers');
        $this->method($this->models, 'wrappers', $this->wrappers, array());
        
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
     * @covers ::config
     * @covers ::<protected>
     */
    public function testConfigException()
    {
        $configSlice = $this->getConfigSlice('fairy', 'test');
        $this->method($this->models, 'modelConfigSlice', $configSlice, array('fairy'), 0);
        $this->method($configSlice, 'get', 'test', array('type', 'database'), 0);
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Model');
        $this->model->config('fairy');
    }
    
    /**
     * @covers ::type
     * @covers ::<protected>
     */
    public function testType()
    {
        $this->assertSame($this->type, $this->model->type());
    }
    
    protected function prepareNullWrappers()
    {
        $this->models = $this->quickMock('\PHPixie\ORM\Models');
        $this->method($this->models, 'wrappers', null, array());
        $this->model = $this->model();
    }
    
    protected function prepareConfig($modelName)
    {
        $configSlice = $this->prepareConfigSlice($modelName, $this->type);
        $config = $this->prepareBuildConfig($modelName, $configSlice);
        $config->model = $modelName;
        
        return $config;
    }
    
    protected function prepareConfigSlice($modelName, $type)
    {
        $configSlice = $this->getConfigSlice();
        $this->method($this->models, 'modelConfigSlice', $configSlice, array($modelName), 0);
        
        $this->method($configSlice, 'get', $type, array('type', 'database'), 0);
        return $configSlice;
    }
    
    protected function getConfigSlice()
    {
        return $this->abstractMock('\PHPixie\Slice\Data');
    }
    
    protected function getData()
    {
        return $this->abstractMock('\PHPixie\ORM\Data\Type');
    }
    
    abstract protected function testWrappersNull();
    abstract protected function prepareBuildConfig($modelName, $configSlice);
    abstract protected function model();
}