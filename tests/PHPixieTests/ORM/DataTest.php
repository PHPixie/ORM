<?php

namespace PHPixieTests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Data
 */
class DataTest extends \PHPixieTests\AbstractORMTest
{
    protected $data;
    protected $documentBuilder;
    
    public function setUp()
    {
        $this->data = $this->quickMock('\PHPixie\ORM\Data', array(
            'buildDocumentBuilder'
        ));
        
        $this->documentBuilder = $this->quickMock('\PHPixie\ORM\Data\Types\Document\Builder');
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::diff
     * @covers ::<protected>
     */
    public function testDiff()
    {
        $set = array('pixie' => 'test');
        $diff = $this->data->diff($set);
        $this->assertInstance($diff, '\PHPixie\ORM\Data\Diff');
        
        $this->assertSame($set, $diff->set());
    }
    
    /**
     * @covers ::removingDiff
     * @covers ::<protected>
     */
    public function testRemovingDiff()
    {
        $set = array('pixie' => 'test');
        $remove = array('fairy' => 'test');
        
        $diff = $this->data->removingDiff($set, $remove);
        $this->assertInstance($diff, '\PHPixie\ORM\Data\Diff\Removing');
        
        $this->assertSame($set, $diff->set());
        $this->assertSame($remove, $diff->remove());
    }
    
    /**
     * @covers ::map
     * @covers ::<protected>
     */
    public function testMap()
    {
        $data = new \stdClass;
        
        $map = $this->data->map($data);
        $this->assertInstance($map, '\PHPixie\ORM\Data\Types\Map', array(
            'dataBuilder' => $this->data
        ));
        
        $this->assertSame($data, $map->originalData());
        
        $map = $this->data->map();
        $this->assertSame(null, $map->originalData());
    }
    
    /**
     * @covers ::document
     * @covers ::<protected>
     */
    public function testDocument()
    {
        $documentNode = $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\Document');
        
        $document = $this->data->document($documentNode);
        $this->assertInstance($document, '\PHPixie\ORM\Data\Types\Document');
        
        $this->assertSame($documentNode, $document->document());
    }
    
    /**
     * @covers ::diffableDocument
     * @covers ::<protected>
     */
    public function testDiffableDocument()
    {
        $documentNode = $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\Document');
        
        $document = $this->data->diffableDocument($documentNode);
        $this->assertInstance($document, '\PHPixie\ORM\Data\Types\Document\Diffable', array(
            'dataBuilder' => $this->data
        ));
        
        $this->assertSame($documentNode, $document->document());
    }
    
    /**
     * @covers ::documentFromData
     * @covers ::<protected>
     */
    public function testDocumentFromData()
    {
        $data - new \stdClass();
        $this->prepareBuildDocumentBuilder();
        $documentNode = $this->prepareDocumentNode($data);
        
        $document = $this->data->documentFromData($data);
        $this->assertInstance($document, '\PHPixie\ORM\Data\Types\Document');
        
        $this->assertSame($documentNode, $document->document());
    }
    
    /**
     * @covers ::documentBuilder
     * @covers ::<protected>
     */
    public function testDocumentBuilder()
    {
        $data = new \PHPixie\ORM\Data();
        $reflection = new \ReflectionClass('\PHPixie\ORM\Data');
		$method = $reflection->getMethod('buildDocumentBuilder');
		$method->setAccessible( true );
        
 		$documentBuilder = $method->invokeArgs($data, array());
        $this->assertInstance($documentBuilder, '\PHPixie\ORM\Data\Types\Document\Builder');
    }
    
    protected function prepareBuildDocumentBuilder()
    {
        $this->method($this->data, 'buildDocumentBuilder', $this->documentBuilder, array(), 'once');
    }
    
    protected function prepareDocumentNode($data)
    {
        $documentNode = $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\Document');
        $this->method($thid->documentBuilder, 'document', $documentNode, array($data), 0);
        return $documentNode;
    }
    
}