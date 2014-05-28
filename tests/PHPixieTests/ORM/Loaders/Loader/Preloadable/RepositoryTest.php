<?php

namespace PHPixieTests\ORM\Loaders\Loader\Preloadable;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Preloadable\Repository
 */
abstract class RepositoryTest extends \PHPixieTests\ORM\Loaders\Loader\PreloadableTest
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