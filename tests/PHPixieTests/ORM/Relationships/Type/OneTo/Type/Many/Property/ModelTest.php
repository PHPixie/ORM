<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model
 */
abstract class ModelTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\Property\ModelTest
{
    protected $config;

    public function setUp()
    {
        $this->config = $this->config();
        parent::setUp();
    }

    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $query = $this->getQuery();
        $this->method($this->handler, 'query', $query, array($this->side, $this->model), 0);
        $this->assertEquals($query, $this->property->query());
    }
    
    protected function prepareLoad($value = null)
    {
        if($value === null)
            $value = $this->value();
        $this->value = $value;
        $this->method($this->handler, 'loadProperty', $this->value, array($this->side, $this->model), 0);
    }

    protected function value()
    {
        return $this->getValue();
    }

    protected function getPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Step');
    }

    protected function getQuery()
    {
        return $this->quickMock('\PHPixie\ORM\Query');
    }

    protected function handler()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Handler');
    }

    protected function side()
    {
        $side = $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side');
        $this->method($side, 'config', $this->config, array());
        return $side;
    }

    protected function config()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side\Config');
    }
    
    abstract protected function getValue();

}
