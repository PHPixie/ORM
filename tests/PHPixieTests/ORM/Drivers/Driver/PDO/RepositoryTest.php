<?php

namespace PHPixieTests\ORM\Drivers\Driver\PDO;

/**
 * @coversDefaultClass \PHPixie\ORM\Drivers\Driver\PDO\Repository
 */
class RepositoryTest extends \PHPixieTests\ORM\Drivers\Driver\SQL\RepositoryTest
{
    protected function repository()
    {
        return new \PHPixie\ORM\Drivers\Driver\PDO\Repository(
            $this->models,
            $this->database,
            $this->dataBuilder,
            $this->inflector,
            $this->modelName,
            $this->config
        );
    }
}