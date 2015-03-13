<?php

namespace PHPixie\Tests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships
 */
class RelationshipsTest extends \PHPixie\Test\Testcase
{
    protected $ormBuilder;
    protected $dependencies;
    
    protected $classMap = array(
        'oneToOne'   => '\PHPixie\ORM\Relationships\Type\OneTo\Type\One',
        'oneToMany'  => '\PHPixie\ORM\Relationships\Type\OneTo\Type\Many',
        'manyToMany' => '\PHPixie\ORM\Relationships\Type\ManyToMany',
        'embedsOne'  => '\PHPixie\ORM\Relationships\Type\Embeds\Type\One',
        'embedsMany' => '\PHPixie\ORM\Relationships\Type\Embeds\Type\Many',
    );

    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
        
        $this->dependencies = array(
            'configs'  => $this->quickMock('\PHPixie\ORM\Configs'),
            'models'   => $this->quickMock('\PHPixie\ORM\Models'),
            'planners' => $this->quickMock('\PHPixie\ORM\Planners'),
            'plans'    => $this->quickMock('\PHPixie\ORM\Plans'),
            'steps'    => $this->quickMock('\PHPixie\ORM\Steps'),
            'loaders'  => $this->quickMock('\PHPixie\ORM\Loaders'),
            'mappers'  => $this->quickMock('\PHPixie\ORM\Mappers')
        );
        
        foreach($this->dependencies as $name => $value) {
            $this->method($this->ormBuilder, $name, $value, array());
        }
        
        $this->relationships = new \PHPixie\ORM\Relationships($this->ormBuilder);
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
            $relationship = $this->relationships->get($name);
            $this->assertInstance($relationship, $class, $this->dependencies);
            $this->assertSame($relationship, $this->relationships->get($name));
        }
    }
    
    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testGetException()
    {
        $this->setExpectedException('\PHPixie\ORM\Exception\Relationship');
        $this->relationships->get('pixie');
    }
}
