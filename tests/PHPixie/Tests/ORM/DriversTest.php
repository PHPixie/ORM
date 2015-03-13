<?php

namespace PHPixie\Tests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers
 */
class DriversTest extends \PHPixie\Test\Testcase
{
    protected $ormBuilder;
    protected $dependencies;
    
    protected $classMap = array(
        'pdo'   => '\PHPixie\ORM\Drivers\Driver\PDO',
        'mongo' => '\PHPixie\ORM\Drivers\Driver\Mongo',
    );

    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
        
        $this->dependencies = array(
            'configs'    => $this->quickMock('\PHPixie\ORM\Configs'),
            'conditions' => $this->quickMock('\PHPixie\ORM\Conditions'),
            'data'       => $this->quickMock('\PHPixie\ORM\Data'),
            'database'   => $this->quickMock('\PHPixie\ORM\Database'),
            'models'     => $this->quickMock('\PHPixie\ORM\Models'),
            'maps'       => $this->quickMock('\PHPixie\ORM\Maps'),
            'mappers'    => $this->quickMock('\PHPixie\ORM\Mappers'),
            'values'     => $this->quickMock('\PHPixie\ORM\Values')
        );
        
        foreach($this->dependencies as $name => $value) {
            $this->method($this->ormBuilder, $name, $value, array());
        }
        
        $this->drivers = new \PHPixie\ORM\Drivers($this->ormBuilder);
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testGet()
    {
        foreach($this->classMap as $name => $class)
        {
            $driver = $this->drivers->get($name);
            $this->assertInstance($driver, $class, $this->dependencies);
            $this->assertSame($driver, $this->drivers->get($name));
        }
    }
}
