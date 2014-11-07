<?php

namespace PHPixie\ORM\Models\Type\Embedded;

interface Entity extends \PHPixie\ORM\Models\Model\Entity
{
    public function setOwnerRelationship($owner, $ownerPropertyName);
    public function unsetOwnerRelationship();
    public function owner();
    public function ownerPropertyName();
}
