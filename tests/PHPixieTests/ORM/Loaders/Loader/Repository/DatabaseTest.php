<?php

namespace PHPixieTests\ORM\Loaders\Loader\Repository;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Repository
 */
abstract class RepositoryTest extends \PHPixieTests\ORM\Loaders\LoaderTest
{
    protected $repository;
    protected $data = array();
    protected $models = array();

    public function setUp()
    {
        foreach(range(0,4) as $i) {
            $this->data[] = new \stdClass;
            $this->models[] = $this->quickMock('\PHPixie\ORM\Model');
        }

        $this->repository = $this->quickMock('\PHPixie\ORM\Repositories\Repository');
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
     * @covers ::offsetExists
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testOffsetExistsGet()
    {
        foreach(range(0, 4) as $i) {
            $this->method($this->repository, 'load', $this->models[$i], array($this->data[$i]), $i);
        }

        foreach($this->data as $key => $value) {
            $this->assertEquals(true, $this->loader->offsetExists($key));
            $this->assertEquals($this->models[$key], $this->loader->getByOffset($key));
        }

        $this->assertEquals(false, $this->loader->offsetExists(5));
        $this->setExpectedException('\Exception');
        $this->assertEquals(false, $this->loader->getByOffset(5));
    }

    /**
     * @covers ::repository
     */
    public function testRepository()
    {
        $this->assertEquals($this->repository, $this->loader->repository());
    }
}
