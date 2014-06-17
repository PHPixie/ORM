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
    
    abstract protected function getType();
}
