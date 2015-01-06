<?php

namespace PHPixie\ORM\Mappers\Preload;

interface Preloadable
{
    public function addPreloader($relationship, $preloader);
    public function getPreloader($relationship);
}