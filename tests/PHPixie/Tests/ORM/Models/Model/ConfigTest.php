<?php

namespace PHPixie\Tests\ORM\Models\Model;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Model\Config
 */
abstract class ConfigTest extends \PHPixie\Tests\ORM\Configs\ConfigTest
{
    protected $type;
    protected $model = 'fairy';
    
    public function setUp()
    {
        $this->sets[] = array(
            $this->slice(array(
                
            )),
            array(
                'type'   => $this->type,
                'model'  => $this->model,
            )
        );
        parent::setUp();
    }
    
    /**
     * @covers \PHPixie\ORM\Configs\Config::__construct
     * @covers \PHPixie\ORM\Models\Model\Config::__construct
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        parent::testConstruct();
    }

}