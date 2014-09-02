<?php

namespace PHPixieTests\ORM\Relationships\Relationship;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Preloader
 */
abstract class PreloaderTest extends \PHPixieTests\AbstractORMTest
{
    protected $preloader;
    protected $loaders;
    protected $relationship;
    protected $side;
    protected $loader;

    public function setUp()
    {
        $this->loaders = $this->quickMock('\PHPixie\ORM\Loaders');
        $this->relationship = $this->relationship();
        $this->side = $this->side();
        $this->loader = $this->loader();
        $this->preloader = $this->preloader();
    }
    
    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Preloader::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::loader
     * @covers ::<protected>
     */
    public function testLoader()
    {
        $this->assertEquals($this->loader, $this->preloader->loader());
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
    
    abstract protected function getModel();
    abstract protected function relationship();
    abstract protected function getSide();
    abstract protected function getConfig();
    abstract protected function loader();
    abstract protected function preloader();
    
}