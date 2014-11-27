<?php

namespace PHPixie\ORM\Values\Updates\Update;

interface Incrementable
{
    public function increment($increments);
    public function getIncrement();
    public function clearIncrement();
}