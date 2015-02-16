<?php

namespace PHPixie\ORM\Conditions\Condition\Collection;

interface RelatedTo extends \PHPixie\ORM\Conditions\Condition\Collection
{
    public function relationship();
    public function setRelationship($relationship);
}