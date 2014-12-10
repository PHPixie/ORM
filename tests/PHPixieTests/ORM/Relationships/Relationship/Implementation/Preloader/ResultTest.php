<?php

namespace PHPixieTests\ORM\Relationships\Relationship\Implementation\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Preloader\Result
 */
abstract class ResultTest extends \PHPixieTests\ORM\Relationships\Relationship\Implementation\PreloaderTest
{
    protected $entities = array();
    protected $preloadedEntities = array();
    protected $loaders;
    protected $side;
    
    public function setUp()
    {
        $this->side = $this->side();
        parent::setUp();
    }
    
    /**
     * @covers ::getEntity
     * @covers ::<protected>
     */
    public function testGetModel()
    {
        $this->prepareMap();
        $this->prepareLoader();
        $models = array_reverse($this->preloadedEntities, true);
        $i = count($models) - 1;
        foreach($models as $id => $model) {
            $this->assertEquals($model, $this->preloader->getEntity($id));
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
    
    protected function prepareMapIdOffsets($idField = 'id')
    {
        $repository = $this->repository(array('idField' => $idField));
        $this->method($this->loader, 'repository', $repository, array(), 0);
        
        $loaderResult = $this->getReusableResult();
        $this->method($this->loader, 'reusableResult', $loaderResult, array(), 1);
        
        $this->method($loaderResult, 'getField', array_keys($this->preloadedEntities), array('id'), 0);

        
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
    
    abstract protected function getSide();
    abstract protected function getConfig();
    abstract protected function prepareMap();
    abstract protected function getExpectedValue($id);
}