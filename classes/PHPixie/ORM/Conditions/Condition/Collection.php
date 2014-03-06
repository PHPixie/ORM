<?php

namespace PHPixie\ORM\Conditions\Condition;

class Collection extends \PHPixie\ORM\Conditions\Condition
{
    public $collectionItems;

    public function __construct($collectionItems)
    {
        $this->collectionItems = $collectionItems;
    }
}
