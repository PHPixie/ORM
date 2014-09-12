<?php

namespace PHPixieTests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Inflector
 */
class InflectorTest extends \PHPixieTests\AbstractORMTest
{
    protected $inflector;
    protected $singularPlural = array(
        'test' => 'tests',
        'repository' => 'repositories',
        'fish' => 'fishes'
    );
    
    public function setUp()
    {
        $this->inflector = new \PHPixie\ORM\Inflector;
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