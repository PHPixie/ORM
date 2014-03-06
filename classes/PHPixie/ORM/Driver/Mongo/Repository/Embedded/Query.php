<?php

namespace PHPixie\ORM\Driver\Mongo\Repository\Embedded;

class Query extends \PHPixie\DB\Driver\Mongo\Query
{
    protected $fieldPrefix;

    public function __construct($db, $connection, $parser, $config, $type, $fieldPrefix)
    {
        parent::__construct($db, $connection, $parser, $config, $type);
        $this->fieldPrefix = $fieldPrefix;
        $this->fields(array('$'));
    }

    public function fields($fields)
    {
        foreach($fields as &$field)
            $field = $this->fieldPrefix.'.'.$field;

        return parent::fields($fields);
    }
}
