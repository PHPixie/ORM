<?php

namespace PHPixie\ORM\Relationships\Relationship;

interface Side
{
    public function type();
    public function config();
    public function modelName();
    public function propertyName();
    public function relationshipType();
}
