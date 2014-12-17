<?php

namespace PHPixie\ORM\Relationships\Type\Embeds\Type\One\Preload;

class Result extends \PHPixie\ORM\Relationships\Type\Embeds\Preload\Result
{
    protected function prepareData()
    {
        $this->data = array();
        $embeddedPath = explode('.', $this->embeddedPrefix);
        foreach($this->reusableResult as $key => $data) {
            $data = $this->getEmbeddedData($data, $embeddedPath);
            if( $data !== null) {
                $this->data[] = $data;
            }
        }
        
    }
}
