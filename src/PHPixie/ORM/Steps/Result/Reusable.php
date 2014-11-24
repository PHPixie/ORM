<?php

namespace PHPixie\ORM\Steps\Result;

interface Reusable extends \PHPixie\ORM\Steps\Result
{
    public function getByOffset($offset);
    public function offsetExists($offset);
}