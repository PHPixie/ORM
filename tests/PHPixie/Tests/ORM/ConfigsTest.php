<?php

namespace PHPixie\Tests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Configs
 */
class ConfigsTest extends \PHPixie\Test\Testcase
{
    protected $configs;
    
    public function setUp()
    {
        $this->configs = new \PHPixie\ORM\Configs();
    }
    
    /**
     * @covers ::inflector
     * @covers ::<protected>
     */
    public function testInflector()
    {
        $inflector = $this->configs->inflector();
        $this->assertInstance($inflector, '\PHPixie\ORM\Configs\Inflector');
        
        $this->assertSame($inflector, $this->configs->inflector());
    }
}