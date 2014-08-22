<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many\Property\Model;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model\Owner
 */
class OwnerTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\Property\ModelTest
{
    protected function prepareLoad($value = null)
    {
        if($value === null)
            $value = $this->value();
        $this->value = $value;
        $this->method($this->handler, 'loadProperty', $this->value, array($this->side, $this->model), 0);
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model($this->handler, $this->side, $this->model);
    }
    
    protected function getValue()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Repository');
    }
}