<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet;

class Steps
{
    public function mapQuery($config, $type, $builder, $result, $immediateOnly = false)
    {
        return new Steps\MapQuery($config, $type, $builder, $result, $immediateOnly);
    }

    public function assertSafeDelete($repository, $config, $result)
    {
        return new Steps\AssertSafeDelete($repository, $config, $result);
    }

    public function moveChild($repository, $config, $childResult, $parentId)
    {
        return new Steps\MoveChild($repository, $config, $childResult, $parentId);
    }

    public function removeNodes($repository, $config, $nodeResult)
    {
        return new Steps\RemoveNodes($repository, $config, $nodeResult);
    }
}
