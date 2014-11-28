<?php

namespace PHPixie\ORM\Values\Updates\Update;

interface Unsetable
{
    public function _unset($field);
    public function getUnset();
    public function clearUnset();
}