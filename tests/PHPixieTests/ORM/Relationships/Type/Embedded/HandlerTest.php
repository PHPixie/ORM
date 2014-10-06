<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Handler
 */
abstract class HandlerTest extends \PHPixieTests\ORM\Relationships\Relationship\HandlerTest
{
    protected function prepareGetDocument($document, $path)
    {
        $path = explode('.', $path);
        return $this->prepareDocumentByPath($document, $path);
    }
    
    protected function prepareGetArrayNode($document, $path)
    {
        $path = explode('.', $path);
        $key = array_pop($path);
        $document = $this->prepareDocumentByPath($document, $path);
        $node = $this->getArrayNode();
        $this->method($document, 'get', $node, array($key));
        return $node;
    }
    
    protected function prepareDocumentByPath($document, $explodedPath)
    {
        foreach($explodedPath as $step) {
            $node = $this->getDocument();
            $this->method($document, 'get', $node, array($step));
            $document = $node;
        }
        return $document;
    }
    
    protected function getData()
    {
        return $this->abstractMock('\PHPixie\ORM\Data\Types\Document');
    }
    
    protected function getArrayNode()
    {
        return $this->abstractMock('\PHPixie\ORM\Data\Types\Document\Node\ArrayNode');
    }
    
    protected function getDocument()
    {
        return $this->abstractMock('\PHPixie\ORM\Data\Types\Document\Node\Document');
    }
    
    protected function getEmbeddedRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Embedded');
    }
}