<?php

namespace PHPixieTests\ORM\Drivers\Driver\Mongo;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\Mongo\Config
 */
class ConfigTest extends \PHPixieTests\ORM\Models\Type\Database\ConfigTest
{
    protected $defaultIdField = '_id';
    protected $plural = array(
        'fairy' => 'fairies'
    );
    protected $driver = 'mongo';
    
    public function setUp()
    {
        $this->sets[] = array(
            $this->slice(array(
                
            )),
            array(
                'collection' => 'fairies',
            )
        );
        
        $this->sets[] = array(
            $this->slice(array(
                'collection' => 'pixies',
            )),
            array(
                'collection' => 'pixies',
            )
        );
        
        parent::setUp();
    }
    
    /**
     * @covers ::__construct
     * @covers PHPixie\ORM\Models\Type\Database\Config::__construct
     * @covers PHPixie\ORM\Models\Model\Config::__construct
     * @covers PHPixie\ORM\Configs\Config::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    protected function getConfig($slice)
    {
        return new \PHPixie\ORM\Drivers\Driver\Mongo\Config($this->inflector, $this->model, $slice);
    }
}