<?php

namespace PHPixie\Tests\ORM\Models\Type\Database;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Type\Database\Config
 */
abstract class ConfigTest extends \PHPixie\Tests\ORM\Models\Model\ConfigTest
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
                'connection' => 'default',
                'driver'     => $this->driver
            )
        );
        
        $this->sets[] = array(
            $this->slice(array(
                'connection' => 'test',
            )),
            array(
                'connection' => 'test',
                'driver'     => $this->driver
            )
        );
        
        parent::setUp();
    }
    
    /**
     * @covers \PHPixie\ORM\Configs\Config::__construct
     * @covers \PHPixie\ORM\Models\Model\Config::__construct
     * @covers \PHPixie\ORM\Models\Type\Database\Config::__construct
     * @covers ::__construct
     * @covers ::<protected>
     * @covers \PHPixie\ORM\Models\Type\Database\Config::<protected>
     */
    public function testConstruct()
    {
        parent::testConstruct();
    }
}