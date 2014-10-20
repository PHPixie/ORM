<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type;

abstract class Embeds extends \PHPixie\ORM\Relationships\Relationship
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
