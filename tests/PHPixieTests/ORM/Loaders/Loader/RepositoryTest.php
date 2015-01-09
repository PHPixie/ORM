<?php

namespace PHPixieTests\ORM\Loaders\Loader;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Repository
 */
abstract class RepositoryTest extends \PHPixieTests\ORM\Loaders\LoaderTest
{
    protected $repository;

    public function setUp()
    {
        $this->repository = $this->getRepository();
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

    /**
     * @covers ::repository
     */
    public function testRepository()
    {
        $this->assertEquals($this->repository, $this->loader->repository());
    }

    protected function getRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Repository');
    }
}
