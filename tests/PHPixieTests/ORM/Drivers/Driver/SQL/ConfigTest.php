<?php

namespace PHPixieTests\ORM\Drivers\Driver\SQL;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\SQL\Config
 */
abstract class ConfigTest extends \PHPixieTests\ORM\Models\Type\Database\ConfigTest
{
    protected $defaultIdField = 'id';
    protected $plural = array(
        'fairy' => 'fairies'
    );
    
    public function setUp()
    {
        $this->sets[] = array(
            $this->slice(array(
                
            )),
            array(
                'table' => 'fairies',
            )
        );
        
        $this->sets[] = array(
            $this->slice(array(
                'table' => 'pixies',
            )),
            array(
                'table' => 'pixies',
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
}