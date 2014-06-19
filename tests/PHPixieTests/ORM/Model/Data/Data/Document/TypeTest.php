<?php

namespace PHPixieTests\ORM\Model\Data\Data\Document;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Data\Data\Document\Type
 */
abstract class TypeTest extends \PHPixieTests\AbstractORMTest
{
    protected $type;
    protected $documentBuilder;
    
    public function setUp()
    {
        $this->documentBuilder = $this->quickMock('\PHPixie\ORM\Model\Data\Data\Document\Builder');
        $this->type = $this->getType();
    }
    
    protected function document()
    {
        return $this->quickMock('\PHPixie\ORM\Model\Data\Data\Document\Type\Document');
    }
    
    protected function documentArray()
    {
        return $this->quickMock('\PHPixie\ORM\Model\Data\Data\Document\Type\DocumentArray');
    }
    
    abstract protected function getType();
}
