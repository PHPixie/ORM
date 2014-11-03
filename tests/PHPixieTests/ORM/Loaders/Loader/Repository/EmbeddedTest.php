<?php

namespace PHPixieTests\ORM\Loaders\Loader\Repository;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Repository\Embedded
 */
abstract class EmbeddedTest extends \PHPixieTests\ORM\Loaders\Loader\RepositoryTest
{

    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Loaders\Loader\Repository::__construct
     * @covers \PHPixie\ORM\Loaders\Loader::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {

    }

    protected function prepareLoad($document, $at = 0)
    {
        $model = $this->abstractMock('\PHPixie\ORM\Repositories\Type\Embedded\Model');
        $this->method($this->repository, 'loadFromDocument', $model, array($document), $at);
        return $model;
    }

    protected function getRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Embedded');
    }
}
