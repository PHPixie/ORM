<?php

namespace PHPixie\Tests\ORM\Data;

/**
 * @coversDefaultClass \PHPixie\ORM\Data\Diff
 */
class DiffTest extends \PHPixie\Test\Testcase
{
    protected $diff;
    
    protected $set;
    
    public function setUp()
    {
        $this->set = (object) array(
            'name'    => 'Trixie',
            'flowers' => 5
        );
        $this->diff = $this->getDiff();
    }
    
    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Data\Diff::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSet()
    {
        return $this->assertEquals($this->set, $this->diff->set());
    }
    
    protected function getDiff()
    {
        return new \PHPixie\ORM\Data\Diff($this->set);
    }
}