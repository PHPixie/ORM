<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type\MAny\Preload;

class Result extends \PHPixie\ORM\Relationships\Type\Embeds\Preload\Result
{
    protected function addEmbeddedData($embeddedData)
    {
        foreach($embeddedData as $data)
        {
            if($data !== null) {
                $this->data[] = $data;
            }
        }
    }
}
