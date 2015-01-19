<?php

namespace PHPixieTests\ORM\Models\Type\Database;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Type\Database\Config
 */
abstract class ConfigTest extends \PHPixieTests\ORM\Models\Model\ConfigTest
{
    protected $type = 'database';
    protected $driver;
    protected $defaultIdField;
    
    public function setUp()
    {
        $this->sets[] = array(
            $this->slice(array(
                
            )),
            array(
                'idField'    => $this->defaultIdField,
                'connection' => 'default',
                'driver'     => $this->driver
            )
        );
        
        $this->sets[] = array(
            $this->slice(array(
                'id'         => 'fairy_id',
                'connection' => 'test',
            )),
            array(
                'idField'    => 'fairy_id',
                'connection' => 'test',
                'driver'     => $this->driver
            )
        );
        
        parent::setUp();
    }
    
    /**
     * @covers ::__construct
     * @covers PHPixie\ORM\Models\Model\Config::__construct
     * @covers PHPixie\ORM\Configs\Config::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
}