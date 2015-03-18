<?php

namespace PHPixie\Tests\ORM\Wrappers\Type\Database;

/**
 * @coversDefaultClass \PHPixie\ORM\Wrappers\Type\Database\Repository
 */
class RepositoryTest extends \PHPixie\Test\Testcase
{
    protected $repository;
    protected $wrapper;
    
    public function setUp()
    {
        $this->repository   = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Repository');
        $this->wrapper = new \PHPixie\ORM\Wrappers\Type\Database\Repository($this->repository);
    }
    
    /**
     * @covers ::<public>
     * @covers ::<protected>
     */
    public function testForwarding()
    {
        $methods = array(
            array('config', true, array()),
            array('modelName', true, array()),
            array('save', null, array('a')),
            array('delete', null, array('a')),
            array('load', true, array('a')),
            array('create', true, array()),
            array('query', true, array()),
            array('connection', true, array()),
            array('databaseSelectQuery', true, array()),
            array('databaseUpdateQuery', true, array()),
            array('databaseDeleteQuery', true, array()),
            array('databaseInsertQuery', true, array()),
            array('databaseCountQuery', true, array()),
        );
        
        foreach($methods as $set) {
            $method = $set[0];
            
            if($set[1] === true) {
                $return = 'test';
                $expect = 'test';
            }elseif($set === false){
                $return = null;
                $expect = $this->wrapper;
            }else{
                $return = $set[1];
                $expect = $set[1];
            }
            
            $params = isset($set[3]) ? $set[3] : $set[2];
            $this->method($this->repository, $method, $return, $params, 0);
            $this->assertSame($expect, call_user_func_array(array($this->wrapper, $method), $set[2]));
        }

    }
}