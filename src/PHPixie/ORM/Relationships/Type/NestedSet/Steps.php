<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet;

class Steps
{
    public function mapQuery($config, $type, $builder, $resultStep, $immediateOnly = false)
    {
        return new Steps\MapQuery($config, $type, $builder, $resultStep, $immediateOnly);
    }
}