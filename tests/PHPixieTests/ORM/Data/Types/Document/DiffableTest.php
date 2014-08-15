<?php

namespace PHPixieTests\ORM\Data\Type\Document;

/**
 * @coversDefaultClass \PHPixie\ORM\Data\Type\Document\Diffable
 */
class DiffableTest extends \PHPixieTests\ORM\Data\Type\DocumentTest
{
    protected $dataBuilder;
    protected $originalData;
    protected $modifiedData;
    
    public function setUp()
    {
        $this->dataBuilder = $this->quickMock('\PHPixie\ORM\Data');
        $this->originalData = (object) array(
            'name'    => 'Trixie',
            'flowers' => 3,
            'magic'   => (object) array(
                'type'  => 'air',
                'spell' => 'wind'
            ),
            'trees' => array('Oak', (object) array('name' => 'Pine')),
            'animals' => array((object) array('name' => 'Fox'))
        );
        $this->modifiedData = clone $this->originalData;
        $this->modifiedData = 'Blum';
        parent::setUp();
    }

    /**
     * @covers ::originalData
     * @covers ::<protected>
     */
    public function testOriginalData()
    {
        $this->type->set('name', 'Blum');
        $this->assertEquals($this->originalData, $this->type->originalData());
    }
    
    /**
     * @covers ::setCurrentAsOriginal
     * @covers ::<protected>
     */
    public function testSetCurrentAsOriginal()
    {
        $data = (object) array('name', 'Blum');
        $this->method($this->document, 'data', $data, array(), 0);
        $this->type->setCurrentAsOriginal();
        $this->assertEquals($data, $this->type->originalData());
    }
    
    /**
     * @covers ::diff
     * @covers ::<protected>
     */
    public function testDiff()
    {
        $diff = $this->diff();
        
        $this->method($this->dataBuilder, 'removingDiff', $diff, array((object) array(), array()), 0);
        $this->method($this->document, 'data', $this->originalData, array(), 0);
        $this->assertEquals($diff, $this->type->diff());
        
        $this->method($this->dataBuilder, 'removingDiff', $diff, array((object) array(
            'name'  => 'Blum',
            'magic.type' => 'water',
            'trees'  => array('Oak', (object) array('name' => 'Maple')),
            'friend' => (object) array(
                'name' => 'Trixie'
            ),
        ), array(
            'flowers',
            'magic.spell'
        )), 0);
        $this->method($this->document, 'data',  (object) array(
            'name'    => 'Blum',
            'trees'   => 2,
            'magic'   => (object) array(
                'type'  => 'water',
            ),
            'friend' => (object) array(
                'name' => 'Trixie'
            ),
            'trees' => array('Oak', (object) array('name' => 'Maple')),
            'animals' => array((object) array('name' => 'Fox'))
        ), array(), 0);
        $this->assertEquals($diff, $this->type->diff());
    }
    
    protected function getType()
    {
        $this->method($this->document, 'data', $this->originalData, array(), 0);
        return new \PHPixie\ORM\Data\Type\Document\Diffable($this->dataBuilder, $this->document);
    }
    
    protected function diff()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Diff\Unsetable');
    }
}    