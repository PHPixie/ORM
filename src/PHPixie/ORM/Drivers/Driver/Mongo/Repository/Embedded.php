<?php

namespace PHPixie\ORM\Drivers\Driver\Mongo\Repository;

class Embedded extends \PHPixie\ORM\Model\Repository\Embedded
{
    public function load($document)
    {
        return $this->ormBuilder->embeddedModel($this, $document, false);
    }

    public function model()
    {
        $document = $this->dataBuilder->document(null);

        return $this->ormBuilder->embeddedModel($this, $document);
    }
}
