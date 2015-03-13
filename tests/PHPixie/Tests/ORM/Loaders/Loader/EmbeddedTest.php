<?php

namespace PHPixie\Tests\ORM\Loaders\Loader;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Embedded
 */
abstract class EmbeddedTest extends \PHPixie\Tests\ORM\Loaders\LoaderTest
{
    protected $embeddedModel;
    protected $modelName = 'pixie';

    public function setUp()
    {
        $this->embeddedModel = $this->quickMock('\PHPixie\ORM\Models\Type\Embedded');
        parent::setUp();
    }

    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Loaders\Loader::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    protected function prepareLoadEntity($document, $at = 0)
    {
        $entity = $this->getEntity();
        $this->method($this->embeddedModel, 'loadEntity', $entity, array($this->modelName, $document), $at);
        return $entity;
    }
    
    protected function getEntity()
    {
        return $this->quickMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
}
