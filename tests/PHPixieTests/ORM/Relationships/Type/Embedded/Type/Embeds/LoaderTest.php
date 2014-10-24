<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds
 */
abstract class LoaderTest extends \PHPixieTests\ORM\Loaders\LoaderTest
{
    protected $config;
    protected $ownerLoader;

    public function setUp()
    {
        $this->config = $this->getConfig();
        $this->ownerLoader = $this->abstractMock('\PHPixie\ORM\Loaders\Loader');
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

    protected abstract function getConfig();
}
