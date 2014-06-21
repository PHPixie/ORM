<?php

namespace PHPixie\ORM\Model\Data\Data;

class Document extends \PHPixie\ORM\Model\Data\Data

    protected $documentBuilder;
    protected $originalData;

    public function __construct($documentBuilder, $originalData)
    {
        parent::__construct($originalData);
        $this->documentBuilder = $documentBuilder;
    }

    public function currentModelData()
    {
        $data = new \stdClass;
        $currentProperties = $this->model->dataProperties();
        foreach($currentProperties as $key => $value) {
            if(is_object($value) && $value instanceof Document\Type)
                $value = $value->currentData();
            $data->$key = $value;
        }
        return $data;
    }

    public function properties()
    {
        return get_object_vars($this->document->currentData());
    }

    public function diff()
    {
        if (($originalData = $this->originalData) === null)
            $originalData = new \stdClass;

        return $this->objectDiff($this->currentData(), $this->originalData);
    }

    protected function objectDiff($new, $old)
    {
        $newData = get_object_vars($new);
        $oldData = get_object_vars($old);
        $unset = array_diff(array_keys($oldData), array_keys($newData));

        $set = array();
        foreach ($newData as $key => $value) {
            if (!array_key_exists($key, $oldData)) {
                $set[$key] = $value;
                continue;
            }

            $oldValue = $oldData[$key];

            if (is_object($value) && is_object($oldValue)) {
                $prefix = $key.'.';

                list($subSet, $subUnset) = $this->getObjectDiff($value, $oldValue);
                foreach ($subSet as $subKey => $subValue) {
                    $set[$prefix.$subKey] = $subValue;
                }
                foreach ($subUnset as $subKey => $subValue) {
                    $unset[$prefix.$subKey] = $subValue;
                }
                continue;
            }

            if (!$this->isEqual($value, $oldValue))
                $set[$key] = $value;
        }

        return $this->diff($set, $unset)
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
