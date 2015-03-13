<?php

namespace PHPixie\Tests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Data
 */
class DataTest extends \PHPixie\Test\Testcase
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
     * @covers ::diffableDocument
     * @covers ::<protected>
     */
    public function testDocument()
    {
        $this->documentTest();
        $this->documentTest(true);
    }
    
    /**
     * @covers ::documentFromData
     * @covers ::diffableDocumentFromData
     * @covers ::<protected>
     */
    public function testDocumentFromData()
    {
        $this->prepareBuildDocumentBuilder();
        $this->documentFromDataTest();
        $this->documentFromDataTest(true);
    }
    
    protected function documentTest($diffable = false)
    {
        $documentNode = $this->getDocumentNode();
        
        if($diffable) {
            $method = 'diffableDocument';
            $class = '\PHPixie\ORM\Data\Types\Document\Diffable';
            $properties = array(
                'dataBuilder' => $this->data
            );
        }else{
            $method = 'document';
            $class = '\PHPixie\ORM\Data\Types\Document';
            $properties = array();
        }
        
        $document = $this->data->$method($documentNode);
        $this->assertInstance($document, $class, $properties);
        
        $this->assertSame($documentNode, $document->document());
    }
    
    protected function documentFromDataTest($diffable = false)
    {
        $data = new \stdClass();
        $documentNode = $this->prepareDocumentNode($data);
        
        if($diffable) {
            $method = 'diffableDocumentFromData';
            $class = '\PHPixie\ORM\Data\Types\Document\Diffable';
            $properties = array(
                'dataBuilder' => $this->data
            );
        }else{
            $method = 'documentFromData';
            $class = '\PHPixie\ORM\Data\Types\Document';
            $properties = array();
        }
        
        $document = $this->data->$method($data);
        $this->assertInstance($document, $class, $properties);
        
        $this->assertSame($documentNode, $document->document());
        
        $documentNode = $this->prepareDocumentNode(null);
        $document = $this->data->$method();
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
        $documentNode = $this->getDocumentNode();
        $this->method($this->documentBuilder, 'document', $documentNode, array($data), 0);
        return $documentNode;
    }
    
    protected function getDocumentNode()
    {
        return $this->quickMock('\PHPixie\ORM\Data\Types\Document\Node\Document');
    }
    
}