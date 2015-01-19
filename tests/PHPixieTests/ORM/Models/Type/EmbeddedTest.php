<?php

namespace PHPixieTests\ORM\Models\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Type\Embedded
 */
class EmbeddedTest extends \PHPixieTests\ORM\Models\ModelTest
{
    protected $type = 'embedded';
    
    /**
     * @covers ::entity
     * @covers ::<protected>
     */
    public function testEntity()
    {
        $this->method($this->wrappers, 'embeddedEntities', array('pixie'), array());
        
        $this->entityTest('fairy', false);
        $this->entityTest('pixie', true);
    }
    
    /**
     * @covers ::<protected>
     */
    public function testWrappersNull()
    {
        $this->prepareNullWrappers();
        $this->entityTest('fairy', false, false);
    }
    
    /**
     * @covers ::<protected>
     */
    public function testBuildInstances()
    {
        $this->model = new \PHPixie\ORM\Models\Type\Embedded(
            $this->models,
            $this->configs,
            $this->maps
        );
        $this->method($this->wrappers, 'embeddedEntities', array(), array(), 0);
        $this->method($this->mapMocks['entity'], 'getPropertyNames', array(), array('fairy'), 0);
        
        $data = $this->getData();
        $this->prepareConfigSlice('fairy', $this->type);
        
        $entity = $this->model->entity('fairy', $data);
        
        $config = $this->model->config('fairy');
        $this->assertInstanceOf('\PHPixie\ORM\Models\Type\Embedded\Config', $config);
        $this->assertSame('fairy', $config->model);
        
        $this->assertInstanceOf('\PHPixie\ORM\Models\Type\Embedded\Implementation\Entity', $entity);
        $this->assertAttributeEquals($this->mapMocks['entity'], 'entityMap', $entity);
        $this->assertAttributeEquals($config, 'config', $entity);
        $this->assertAttributeEquals($data, 'data', $entity);
    }
    
    protected function entityTest($modelName, $isWrapped, $wrapperAt = 0)
    {
        $data = $this->getData();
        $config = $this->prepareConfig($modelName);
        
        $entity  = $this->getEntity();
        $this->method($this->model, 'buildEntity', $entity, array(
            $config, 
            $data
        ), 1);
        
        if($isWrapped) {
            $wrapped  = $this->getEntity();
            $this->method($this->wrappers, 'embeddedEntityWrapper', $wrapped, array($entity), $wrapperAt);
            $entity = $wrapped;
        }
        
        $this->assertSame($entity, $this->model->entity($modelName, $data));
    }
    
    protected function prepareBuildConfig($modelName, $configSlice)
    {
        $config =  $this->getConfig();
        $this->method($this->model, 'buildConfig', $config, array($modelName, $configSlice), 0);
        return $config;
    }
    
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Models\Type\Embedded\Config');
    }
    
    protected function getEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Embedded\Entity');
    }
    
    protected function model()
    {
        return $this->getMock(
            '\PHPixie\ORM\Models\Type\Embedded',
            array('buildConfig', 'buildEntity'),
            array($this->models, $this->configs, $this->maps)
        );
    }
}