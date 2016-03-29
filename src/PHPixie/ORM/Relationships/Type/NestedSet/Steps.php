<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet;

class Steps
{
    public function mapQuery($side, $builder, $resultStep, $immediateOnly = false)
    {
        return new Steps\MapQuery($side, $builder, $resultStep, $immediateOnly);
    }
}