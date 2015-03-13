<?php

namespace PHPixie\Tests\ORM\Conditions\Condition\Field;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions\Condition\Field\Operator
 */
class OperatorTest extends \PHPixie\Tests\Database\Conditions\Condition\Field\OperatorTest
{
    
    protected function condition()
    {
        return new \PHPixie\ORM\Conditions\Condition\Field\Operator(
            $this->field,
            $this->operator,
            $this->values
        );
    }
    
}