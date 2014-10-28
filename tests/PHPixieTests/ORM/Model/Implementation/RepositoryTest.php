<?php

namespace PHPixieTests\ORM\Model\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Model\Implementation\Repository
 */
abstract class RepositoryTest extends \PHPixieTests\AbstractORMTest
{
    protected $repository;
    protected $modelName = 'fairy';

    public function setUp()
    {
        $this->databuilder = $this->quickMock('\PHPixie\ORM\Data');
        $this->repository = $this->repository();
    }

    public function modelName()
    {
        $this->assertEquals($this->modelName, $this->repository->modelName());
    }

    abstract protected function repository();
}
