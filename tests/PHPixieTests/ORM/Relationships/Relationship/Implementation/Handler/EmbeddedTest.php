<?php

namespace PHPixieTests\ORM\Relationships\Relationship\Implementation\Handler;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Implementation\Handler\Embedded
 */
abstract class EmbeddedTest extends \PHPixieTests\ORM\Relationships\Relationship\Implementation\HandlerTest
{
    protected function prepareGetDocument($document, $path)
    {
        $path = explode('.', $path);
        return $this->prepareDocumentByPath($document, $path);
    }

    protected function prepareGetArrayNode($document, $path)
    {
        list($document, $key) = $this->prepareGetParentDocumentAndKey($document, $path);
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

    protected function prepareGetParentDocumentAndKey($document, $path)
    {
        $path = explode('.', $path);
        $key = array_pop($path);
        $subdocument = $this->prepareDocumentByPath($document, $path);
        return array($subdocument, $key);
    }

    protected function getDatabaseDocumentQuery()
    {
        return $this->abstractMock('\PHPixie\Database\Type\Document\Query\Items');
    }
    
    protected function getDocumentConditionContainer()
    {
        return $this->abstractMock('\PHPixie\Database\Type\Document\Conditions\Builder\Container');
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
    
    protected function getEmbeddedEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Embedded\Entity');
    }
}
