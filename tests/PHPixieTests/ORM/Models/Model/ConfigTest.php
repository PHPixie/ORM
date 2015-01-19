<?php

namespace PHPixieTests\ORM\Models\Model;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Model\Config
 */
abstract class ConfigTest extends \PHPixieTests\ORM\Configs\ConfigTest
{
    protected $type;
    protected $model = 'fairy';
    
    public function setUp()
    {
        $this->sets[] = array(
            $this->slice(array(
                
            )),
            array(
                'type'       => $this->type,
                'model'  => $this->model,
            )
        );
        parent::setUp();
    }
    
    /**
     * @covers ::__construct
     * @covers PHPixie\ORM\Configs\Config::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }

}