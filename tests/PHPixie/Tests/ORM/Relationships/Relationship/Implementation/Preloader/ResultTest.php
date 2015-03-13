<?php

namespace PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result
 */
abstract class ResultTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\PreloaderTest
{
    protected $entities = array();
    protected $preloadedEntities = array();
    protected $loaders;
    protected $side;
    protected $modelConfig;
    protected $result;
    protected $loader;
    
    public function setUp()
    {
        $this->side = $this->side();
        $this->modelConfig = $this->modelConfig();
        $this->result = $this->result();
        $this->loader = $this->loader();
        parent::setUp();
    }
    
    /**
     * @covers \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader\Result::__construct
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::getEntity
     * @covers ::<protected>
     */
    public function testGetEntity()
    {
        $this->prepareMap();
        $this->prepareLoader();
        $entities = array_reverse($this->preloadedEntities, true);
        $i = count($entities) - 1;
        foreach($entities as $id => $entity) {
            $this->assertEquals($entity, $this->preloader->getEntity($id));
            $i--;
        }
    }
    
    /**
     * @covers ::loadProperty
     * @covers ::<protected>
     */
    public function testLoadProperty()
    {
        $this->prepareMap();
        $this->prepareLoader();
        foreach($this->entities as $entityId => $entity) {
            $property = $this->property($entity, $this->getExpectedValue($entityId));
            $this->preloader->loadProperty($property);
        }
    }
    
    protected function prepareMapIdOffsets($at = 0, $idField = 'id')
    {
        $this->method($this->result, 'getField', array_keys($this->preloadedEntities), array($idField), $at);
    }
    
    protected function prepareLoader()
    {
        $entityOffsets = array_values($this->preloadedEntities);
        $this->loader
                ->expects($this->any())
                ->method('getByOffset')
                ->will($this->returnCallback(function($offset) use ($entityOffsets) {
                    return $entityOffsets[$offset];
                }));
    }
    
    protected function mapConfig($config, $data)
    {
        $config
            ->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($key) use($data){
                return $data[$key];
            }));
        foreach($data as $key => $value)
            $config->$key = $value;
    }
    
    protected function side()
    {
        $config = $this->getConfig();
        $this->mapConfig($config, $this->configData);
        $side = $this->getSide();
        $this->method($side, 'config', $config, array());
        return $side;
    }
    
    protected function repository($configData = array())
    {
        $repository = $this->getDatabaseRepository();
        
        $config = $this->getDatabaseConfig();
        foreach($configData as $key => $value) {
            $config->$key = $value;
        }

        $this->method($repository, 'config', $config, array());
        return $repository;
    }
    
    protected function result()
    {
        return $this->getReusableResult();
    }
    
    protected function modelConfig()
    {
        $config = $this->getDatabaseModelConfig();
        $this->mapConfig($config, array('idField' => 'id'));
        return $config;
    }

    protected function getDatabaseModelConfig()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Config');
    }
    
    protected function getReusableResult()
    {
        return $this->quickMock('\PHPixie\ORM\Steps\Result\Reusable');
    }
    
    protected function getEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
    
    protected function getDatabaseRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Repository');
    }
    
    protected function getDatabaseConfig()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Config');
    }    
    
    abstract protected function loader();
    
    abstract protected function getSide();
    abstract protected function getConfig();
    abstract protected function prepareMap();
    abstract protected function getExpectedValue($id);
}