<?php

namespace PHPixie\ORM\Mapper\Preload;

interface Source
{
    public function loader();
    public function preloadingProxy();
}