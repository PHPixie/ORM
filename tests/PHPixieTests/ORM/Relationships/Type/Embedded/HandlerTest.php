<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Handler
 */
abstract class HandlerTest extends \PHPixieTests\ORM\Relationships\Relationship\HandlerTest
{
    protected function prepareGetDocument($model, $path)
    {
        $data = $this->getData();
        $this->method($model, 'data', $data, array());
        
        $document = $this->getDocument();
        $this->method($data, 'document', $document, array());
    }
    
    protected function getData()
    {
        return $this->abstractMock('\PHPixie\ORM\Data\Type\Document');
    }
    
    protected function getArrayNode()
    {
        return $this->abstractMock('\PHPixie\ORM\Data\Type\Document\Node\ArrayNode');
    }
    
    protected function getDocument()
    {
        return $this->abstractMock('\PHPixie\ORM\Data\Type\Document\Node\Document');
    }
}