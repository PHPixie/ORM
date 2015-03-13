<?php

namespace PHPixie\Tests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Repositories
 */
class RepositoriesTest extends \PHPixie\Test\Testcase
{
    protected $models;
    protected $databaseModel;
    
    protected $repositories;
    
    public function setUp()
    {
        $this->models = $this->quickMock('\PHPixie\ORM\Models');
        $this->databaseModel = $this->quickMock('\PHPixie\ORM\Models\Type\Database');
        
        $this->method($this->models, 'database', $this->databaseModel, array());
        
        $this->repositories = new \PHPixie\ORM\Repositories($this->models);
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::get
     * @covers ::<protected>
     */
    public function testGet()
    {
        $repository = $this->getRepository();
        $this->method($this->databaseModel, 'repository', $repository, array('fairy'), 0);
        
        $this->assertSame($repository, $this->repositories->get('fairy'));
    }
    
    protected function getRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Repository');
    }
}