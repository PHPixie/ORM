<?php

namespace PHPixie\ORM\Data\Types\Document;

class Diffable extends \PHPixie\ORM\Data\Types\Document implements \PHPixie\ORM\Data\Type\Diffable
{
    protected $dataBuilder;
    protected $originalData;
    
    public function __construct($dataBuilder, $document)
    {
        $this->dataBuilder = $dataBuilder;
        parent::__construct($document);
        $this->setCurrentAsOriginal();
    }
    
    public function originalData()
    {
        return $this->originalData;
    }
    
    public function setCurrentAsOriginal()
    {
        $this->originalData = $this->data();
    }
    
    public function diff()
    {
        if (($originalData = $this->originalData) === null)
            $originalData = new \stdClass;

        list($set, $unset) = $this->objectDiff($this->data(), $this->originalData);
        return $this->dataBuilder->removingDiff((object) $set, $unset);
    }

    protected function objectDiff($new, $old)
    {
        $newData = get_object_vars($new);
        $oldData = get_object_vars($old);
        $unset = array_values(array_diff(array_keys($oldData), array_keys($newData)));

        $set = array();
        foreach ($newData as $key => $value) {
            if (!array_key_exists($key, $oldData)) {
                $set[$key] = $value;
                continue;
            }

            $oldValue = $oldData[$key];

            if (is_object($value) && is_object($oldValue)) {
                $prefix = $key.'.';

                list($subSet, $subUnset) = $this->objectDiff($value, $oldValue);
                foreach ($subSet as $subKey => $subValue) {
                    $set[$prefix.$subKey] = $subValue;
                }
                
                foreach ($subUnset as $subValue) {
                    $unset[] = $prefix.$subValue;
                }
                continue;
            }

            if (!$this->isEqual($value, $oldValue))
                $set[$key] = $value;
        }

        return array($set, $unset);
    }

    protected function isArrayEqual($new, $old)
    {
        if(array_keys($new) !== array_keys($old))

            return false;

        foreach($new as $key => $value)
            if(!$this->isEqual($value, $old[$key]))

                return false;

        return true;
    }

    protected function isObjectEqual($new, $old)
    {
        $newData = get_object_vars($new);
        $oldData = get_object_vars($old);

        return $this->isArrayEqual($newData, $oldData);
    }

    protected function isEqual($new, $old)
    {
        $type = gettype($new);

        if($type !== gettype($old))

            return false;

        if($type === 'array')

            return $this->isArrayEqual($new, $old);

        if ($type === 'object')
            return $this->isObjectEqual($new, $old);

        return $new === $old;
    }
}