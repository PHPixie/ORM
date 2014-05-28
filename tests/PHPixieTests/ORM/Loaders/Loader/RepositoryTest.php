<?php

namespace PHPixieTests\ORM\Loaders\Loader;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Repository
 */
abstract class RepositoryTest extends \PHPixieTests\ORM\Loaders\LoaderTest
{
    protected $repository;
    protected $data = array();
    
    public function setUp()
    {
        foreach(range(0,4) as $i) {
            $this->data[] = new \stdClass;
        }
            
        $this->repository = $this->quickMock('\PHPixie\ORM\Model\Repository');
        parent::setUp();
    }    
    
    /**
     * @covers ::repository
     */
    public function testRepository()
    {
        $this->assertEquals($this->repository, $this->loader->repository());
    }    
}