<?php

namespace PHPixie\ORM\Relationships\Types\Embedded\Type;

abstract class Embeds extends \PHPixie\ORM\Relationships\Relationship\Type
{
    protected $embeddedGroupMapper;

    public function __construct($ormBuilder, $embeddedGroupMapper)
    {
        parent::__construct($ormBuilder);
        $this->embeddedGroupMapper = $embeddedGroupMapper;
    }

    public function queryProperty($side, $query)
    {
        throw new \PHPixie\ORM\Exception\Mapper("Query properties do not exist for embbeded relationships.");
    }

    abstract public function loader($config, $ownerLoader);
    abstract public function preloader($side, $loader);
}
