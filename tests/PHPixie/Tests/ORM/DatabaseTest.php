<?php

namespace PHPixie\Tests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Database
 */
class DatabaseTest extends \PHPixie\Test\Testcase
{
    protected $database;
    protected $ormDatabase;
    
    public function setUp()
    {
        $this->database = $this->quickMock('\PHPixie\Database');
        $this->ormDatabase = new \PHPixie\ORM\Database($this->database);
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::connection
     * @covers ::<protected>
     */
    public function testConnection()
    {
        $connection = $this->getConnection();
        $this->method($this->database, 'get', $connection, array('pixie'), 0);
        
        $this->assertSame($connection, $this->ormDatabase->connection('pixie'));
    }
    
    /**
     * @covers ::connectionDriverName
     * @covers ::<protected>
     */
    public function testConnectionDriverName()
    {
        $this->method($this->database, 'connectionDriverName', 'pdo', array('pixie'), 0);
        
        $this->assertSame('pdo', $this->ormDatabase->connectionDriverName('pixie'));
    }
       
    protected function getConnection()
    {
        return $this->abstractMock('\PHPixie\Database\Connection');
    }

}