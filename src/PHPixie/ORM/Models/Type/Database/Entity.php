<?php

namespace PHPixie\ORM\Models\Type\Database;

interface Entity extends \PHPixie\ORM\Models\Model\Entity
{
    public function id();
    public function setId($id);
    public function isDeleted();
    public function setIsDeleted($isDeleted);
    public function isNew();
    public function setIsNew($isNew);
    public function save();
    public function delete();
}