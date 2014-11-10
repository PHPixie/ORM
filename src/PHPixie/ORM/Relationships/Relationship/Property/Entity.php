<?php

namespace PHPixie\ORM\Relationships\Relationship\Property;

interface Entity extends \PHPixie\ORM\Relationships\Relationship\Property
{
    public function __invoke();
    public function reload();
    public function reset();
    public function entity();
    public function value();
    public function setValue($value);
    public function isLoaded();
}
