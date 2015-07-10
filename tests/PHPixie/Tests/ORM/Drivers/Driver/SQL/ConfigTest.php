<?php

namespace PHPixie\Tests\ORM\Drivers\Driver\SQL;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\SQL\Config
 */
abstract class ConfigTest extends \PHPixie\Tests\ORM\Models\Type\Database\ConfigTest
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
                'idField' => 'id',
                'table'   => 'fairies',
            )
        );
        
        $this->sets[] = array(
            $this->slice(array(
                'id'    => 'pixieId',
                'table' => 'pixies',
            )),
            array(
                'idField' => 'pixieId',
                'table'   => 'pixies',
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
     * @covers \PHPixie\ORM\Models\Type\Database\Config::<protected>
     */
    public function testConstruct()
    {
        parent::testConstruct();
    }
}