<?php

namespace PHPixie\Tests\ORM\Loaders\Loader;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Repository
 */
abstract class RepositoryTest extends \PHPixie\Tests\ORM\Loaders\LoaderTest
{
    protected $repository;

    public function setUp()
    {
        $this->repository = $this->getRepository();
        parent::setUp();
    }

    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Loaders\Loader\Repository::__construct
     * @covers \PHPixie\ORM\Loaders\Loader::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {

    }
    
    /**
     * @covers ::repository
     * @covers ::<protected>
     */
    public function testRepository()
    {
        $this->assertSame($this->repository, $this->loader->repository());
    }
    
    protected function prepareLoadEntity($data, $at = 0)
    {
        $entity = $this->getEntity();
        $this->method($this->repository, 'load', $entity, array($data), 0);
        return $entity;
    }
    
    protected function getRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Repository');
    }
    
    protected function getEntity()
    {
        return $this->quickMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
    
}
