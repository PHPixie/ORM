<?php

namespace PHPixie\Tests\ORM\Models\Type;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Type\Embedded
 */
class EmbeddedTest extends \PHPixie\Tests\ORM\Models\ModelTest
{
    protected $type = 'embedded';
    
    protected $maps;
    protected $data;
    
    public function setUp()
    {
        $this->maps = $this->quickMock('\PHPixie\ORM\Maps');
        
        $this->mapMocks['entityProperty'] = $this->quickMock('\PHPixie\ORM\Maps\Map\Property\Entity');
        $this->mapMocks['queryProperty'] = $this->quickMock('\PHPixie\ORM\Maps\Map\Property\Query');
        
        foreach($this->mapMocks as $type => $mock) {
            $this->method($this->maps, $type, $this->mapMocks[$type], array());
        }
        
        $this->data = $this->quickMock('\PHPixie\ORM\Data');
        
        parent::setUp();
    }
    
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
     * @covers ::loadEntity
     * @covers ::<protected>
     */
    public function testLoadEntity()
    {
        $this->prepareNullWrappers();
        
        $document = $this->getDocument();
        $documentData = $this->getData();
        
        $this->method($this->data, 'document', $documentData, array($document), 0);
        $entity = $this->prepareEntity($documentData, 'pixie', false);
        
        $this->assertSame($entity, $this->model->loadEntity('pixie', $document));
    }
    
    /**
     * @covers ::loadEntityFromData
     * @covers ::<protected>
     */
    public function testLoadEntityFromData()
    {
        $this->prepareNullWrappers();
        
        $data = new \stdClass();
        $documentData = $this->getData();
        
        $this->method($this->data, 'documentFromData', $documentData, array($data), 0);
        $entity = $this->prepareEntity($documentData, 'pixie', false);
        
        $this->assertSame($entity, $this->model->loadEntityFromData('pixie', $data));
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
            $this->data,
            $this->maps
        );
        
        $this->method($this->wrappers, 'embeddedEntities', array(), array(), 0);
        
        $data = $this->getData();
        $this->prepareConfigSlice('fairy', $this->type);
        
        $entity = $this->model->entity('fairy', $data);
        
        $config = $this->model->config('fairy');
        $this->assertInstanceOf('\PHPixie\ORM\Models\Type\Embedded\Config', $config);
        $this->assertSame('fairy', $config->model);
        
        $this->assertInstanceOf('\PHPixie\ORM\Models\Type\Embedded\Implementation\Entity', $entity);
        $this->assertAttributeEquals($this->mapMocks['entityProperty'], 'entityPropertyMap', $entity);
        $this->assertAttributeEquals($config, 'config', $entity);
        $this->assertAttributeEquals($data, 'data', $entity);
    }
    
    protected function entityTest($modelName, $isWrapped, $wrapperAt = 0)
    {
        $data = $this->getData();
        $entity = $this->prepareEntity($data, $modelName, $isWrapped, $wrapperAt);
        $this->assertSame($entity, $this->model->entity($modelName, $data));
    }
    
    protected function prepareEntity($data, $modelName, $isWrapped, $wrapperAt = 0)
    {
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
        
        return $entity;
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
    
    protected function getData()
    {
        return $this->abstractMock('\PHPixie\ORM\Data\Types\Document');
    }
    
    protected function getDocument()
    {
        return $this->abstractMock('\PHPixie\ORM\Data\Types\Document\Node\Document');
    }
    
    protected function model()
    {
        return $this->getMock(
            '\PHPixie\ORM\Models\Type\Embedded',
            array(
                'buildConfig',
                'buildEntity'
            ),
            array(
                $this->models,
                $this->configs,
                $this->data,
                $this->maps
            )
        );
    }
}