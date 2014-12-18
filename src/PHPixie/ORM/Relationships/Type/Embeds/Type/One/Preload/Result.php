<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type\One\Preload;

class Result extends \PHPixie\ORM\Relationships\Type\Embeds\Preload\Result
{
    protected function addEmbeddedData($embeddedData)
    {
        $this->data[] = $embeddedData;
    }
}
