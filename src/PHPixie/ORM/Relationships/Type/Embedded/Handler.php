<?php

namespace PHPixie\ORM\Relationships\Type\Embedded;

abstract class Handler extends \PHPixie\ORM\Relationships\Relationship\Handler
{

    protected function explodePath($path)
    {
        return explode('.', $path);
    }

    protected function getArrayNode($path)
    {
        $path = $this->explodePath();
        $document = $this->getDocument($path);
        if(!isset
    }

}
