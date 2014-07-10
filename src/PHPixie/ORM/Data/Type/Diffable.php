<?php

namespace PHPixie\ORM\Data\Type;

interface Diffable extends \PHPixie\ORM\Data\Type
{
    public function diff();
    public function originalData();
    public function setCurrentAsOriginal();
}