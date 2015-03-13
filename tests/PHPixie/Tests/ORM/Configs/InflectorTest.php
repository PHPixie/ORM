<?php

namespace PHPixie\Tests\ORM\Configs;

/**
 * @coversDefaultClass \PHPixie\ORM\Configs\Inflector
 */
class InflectorTest extends \PHPixie\Test\Testcase
{
    protected $inflector;
    protected $singularPlural = array(
        'test' => 'tests',
        'repository' => 'repositories',
        'fish' => 'fishes'
    );
    
    public function setUp()
    {
        $this->inflector = new \PHPixie\ORM\Configs\Inflector;
    }

    /**
     * @covers ::plural
     * @covers ::<protected>
     */
    public function testPlural()
    {
        foreach($this->singularPlural as $singular => $plural) {
            $this->assertEquals($plural, $this->inflector->plural($singular));
        }
        
        $this->assertEquals($plural, $this->inflector->plural($singular));
    }
    
    /**
     * @covers ::singular
     * @covers ::<protected>
     */
    public function testSingular()
    {
        foreach($this->singularPlural as $singular => $plural) {
            $this->assertEquals($singular, $this->inflector->singular($plural));
        }
        
        $this->assertEquals($singular, $this->inflector->singular($plural));
    }

                            
}