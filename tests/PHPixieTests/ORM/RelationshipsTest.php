<?php

namespace PHPixieTests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships
 */
class RelationshipsTest extends \PHPixieTests\AbstractORMTest
{
    protected $ormBuilder;
    protected $relationships;

    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
        $this->relationships = new \PHPixie\ORM\Relationships($this->ormBuilder);
    }

    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testGet()
    {
        $relationships = array(
            'oneToOne',
            'oneToMany',
            'manyToMany',
            'embedsOne',
            'embedsMany'
        );

        foreach($relationships as $name)
        {
            $relationship = $this->relationships->get($name);
            $class = '\PHPixie\ORM\Relationships\Type\\'.ucfirst($name);
            $this->assertInstanceOf($class, $relationship);
            $this->assertSame($relationship, $this->relationships->get($name));
        }

        $embedsMapper =
    }
}
