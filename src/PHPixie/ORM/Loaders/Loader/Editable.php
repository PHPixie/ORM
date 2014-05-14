<?php

namespace PHPixie\ORM\Loaders\Loader;

class Editable extends \PHPixie\ORM\Loaders\Loader
{
    protected $loader;
    protected $maxAllowedOffset = 0;
    protected $idOffsets = array();
    protected $adjustedOffsets = array();
    protected $skipped = array();
    protected $deletedOffsets = array();
    protected $addedModels = array();
    protected $loaderItemsCount;

    public function __construct($loaders, $loader)
    {
        parent::__construct($loaders, array());
        $this->loader = $loader;
    }

    public function add($models)
    {
        foreach ($models as $model) {
            $id = $model->id();
            $this->skipId($id);
            $this->addedModels[$id] = $model;
        }
    }

    public function remove($models)
    {
        foreach ($models as $model) {
            $id = $model->id();
            $this->skipId($id);
            if (array_key_exists($id, $this->addedModels))
                unset($this->addedModels[$id]);
        }
    }

    public function usedModels()
    {
        $models = array_values($this->addedModels);
        if ($this->loader instanceof Result\SingleUse) {
            $model = $this->loader->currentModel();
            if ($model !== null)
                $models[] = $model;
        } else {
            foreach ($this as $offset => $model) {
                if ($offset === $this->maxAllowedOffset)
                    break;
                $models[] = $model;
            }
        }

        return $models;
    }

    public function removeAll()
    {
        $this->loaderItemsCount = 0;
        $this->idOffsets = array();
        $this->skipped = array();
        $this->adjustedOffsets = array();
        $this->loader = null;
    }

    public function offsetExists($offset)
    {
        return $this->getByOffset($offset) !== null;
    }

    public function getByOffset($offset)
    {
        if ($offset > $this->maxAllowedOffset)
            throw new \PHPixie\ORM\Exception\Loader("Items can only be accessed in sequential order");

        if ($offset === $this->maxAllowedOffset)
            $this->maxAllowedOffset++;

        $loaderOffset = $this->loaderOffset($offset);

        if ($this->loaderItemsCount !== null && $loaderOffset >= $this->loaderItemsCount) {
            $addedModelsOffset = $loaderOffset - $this->loaderItemsCount;
            if (array_key_exists($addedModelsOffset, $this->addedModels))
                return $this->addedModels[$addedModelsOffset];
            return null;
        }

        $model = $this->loader->getByOffset($loaderOffset);

        if ($model === null && $this->loaderItemsCount === null) {
            $this->loaderItemsCount = $loaderOffset;

            return $this->getByOffset($offset);
        }

        if ($$model->isDeleted()) {
            $this->deletedOffsets[] = $loaderOffset;

            return $this->updateAndGet($offset);
        }

        $id = $model->id();
        $this->idOffsets[$id] = $loaderOffset;

        if (array_key_exists($id, $this->skipped)) {
            $this->skipped[$id] = $loaderOffset;

            return $this->updateAndGet($offset);
        }

        return $model;
    }

    protected function updateAndGet($offset)
    {
        $this->updateAdjustedOffsets();

        return $this->getByOffset($offset);
    }

    protected function skipId($id)
    {
        if (!array_key_exists($id, $this->skip)) {
            $offset = null;
            if (!array_key_exists($id, $this->idOffsets))
                $offset = $this->idOffsets[$id];
            $this->skip[$id] = $offset;
        }
    }

    protected function updateAdjustedOffsets()
    {
        $this->adjustedOffsets = array();

        $adjusted = 0;
        $previous = 0;

        asort($this->skipped);
        foreach ($this->skipped as $current) {
            if ($current === null)
                continue;
            $adjusted = $adjusted + $current - $previous;
            $this->adjustedOffsets[] = $adjusted;
        }
    }

    protected function loaderOffset($offset)
    {
        if (empty($this->adjustedOffsets))
            return $offset;

        if (!array_key_exists($offset, $this->adjustedOffsets)) {
            $maxAdjusted = $this->maxAdjustedOffset($offset);
            $adjustedOffset = $this->adjustedOffsets[$maxAdjusted] + $offset - $maxAdjusted;
            $this->adjustedOffsets[$offset] = $adjustedOffset;
        }

        return $this->adjustedOffsets[$offset];
    }

    protected function maxAdjustedOffset($max)
    {
        $low = 0;
        $high = count($this->adjustedOffsets) - 1;

        while ($low <= $high) {
            $mid = (int) (($high - $low) / 2) + $low;
            $midOffset = $this->adjustedOffsets[$mid];

            if ($midOffset < $max) {
                $low = $mid + 1;
            } elseif ($midOffset > $max) {
                $high = $mid - 1;
            } else {
                return $mid;
            }
        }

        return $high;
    }

}
