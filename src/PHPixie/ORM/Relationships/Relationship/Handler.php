<?php

namespace PHPixie\ORM\Relationships\Relationship;

interface Handler
{
    public function mapPreload();
    public function mapQuery();
    public function handleDelete();
}