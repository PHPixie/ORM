<?php

namespace PHPixie\Tests\ORM\Conditions\Condition\Field;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions\Condition\Field\Subquery
 */
class SubqueryTest extends \PHPixie\Tests\Database\Conditions\Condition\Field\ImplementationTest
{
    protected $subquery;
    protected $subqueryField = 'pixie_id';
    
    public function setUp()
    {
        $this->subquery = $this->getDatabaseQuery();
        parent::setUp();
    }
    
    /**
     * @covers ::subquery
     * @covers ::setSubquery
     * @covers ::subqueryFiled
     * @covers ::setSubqueryField
     */
    public function testProperties()
    {
        $this->assertEquals($this->subquery, $this->condition->subquery());
        $this->assertEquals($this->subqueryField, $this->condition->subqueryField());
        
        $subquery = $this->getDatabaseQuery();
        $this->assertSame($this->condition, $this->condition->setSubqueryField($subquery));
        $this->assertSame($this->condition, $this->condition->setSubqueryField('b'));
        
        $this->assertEquals($subquery, $this->condition->subquery());
        $this->assertEquals('b', $this->condition->subqueryField());
    }
    
    protected function getDatabaseQuery()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
    }
    
    protected function condition()
    {
        return new \PHPixie\ORM\Conditions\Condition\Field\Subquery(
            $this->field,
            $this->subquery,
            $this->subqueryField
        );
    }
    
}