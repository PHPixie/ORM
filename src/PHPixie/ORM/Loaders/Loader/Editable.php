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
    protected $addedOffsets = array();
    protected $loaderItemsCount = null;

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
            $this->addedModels[] = $model;
            $this->addedOffsets[$id] = count($this->addedModels) - 1;
        }
    }

    public function remove($models)
    {
        foreach ($models as $model) {
            $id = $model->id();
            $this->skipId($id);
            if (array_key_exists($id, $this->addedOffsets)){
                $offset = $this->addedOffsets[$id];
                unset($this->addedModels[$offset]);
                unset($this->addedOffsets[$id]);
            }
        }
    }

    public function usedModels()
    {
        $models = $this->addedModels;
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
        print_r(array($offset, $loaderOffset, $this->loaderItemsCount));
        if ($this->loaderItemsCount !== null && $loaderOffset >= $this->loaderItemsCount) {
            $addedModelsOffset = $loaderOffset - $this->loaderItemsCount;
            if (array_key_exists($addedModelsOffset, $this->addedModels))
                return $this->addedModels[$addedModelsOffset];
            return null;
        }
        
        if(!$this->loader->offsetExists($loaderOffset)){
            
            if ($this->loaderItemsCount === null) {
                $this->loaderItemsCount = $loaderOffset-1;
                return $this->getByOffset($offset);
            }
            
            return null;
        }
        
        $model = $this->loader->getByOffset($loaderOffset);

        if ($model->isDeleted()) {
            $this->deletedOffsets[] = $loaderOffset;
            
            echo('--'.$offset.$loaderOffset);
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
        var_Dump($offset);
        return $this->getByOffset($offset);
    }

    protected function skipId($id)
    {
        if (!array_key_exists($id, $this->skipped)) {
            $offset = null;
            if (array_key_exists($id, $this->idOffsets)) {
                $offset = $this->idOffsets[$id];
                $this->skipped[$id] = $offset;
            }
        }
    }

    protected function updateAdjustedOffsets()
    {
        $this->adjustedOffsets = array();

        $adjusted = 0;
        $previous = 0;
        
        $skipped = $this->skipped;
        foreach($this->deletedOffsets as $deletedOffset)
            $skipped[]=$deletedOffset;
        
        asort($skipped);
        foreach ($skipped as $current) {
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
        print_r($this->adjustedOffsets);
        
        if (!array_key_exists($offset, $this->adjustedOffsets)) {
            $maxAdjusted = $this->maxAdjustedOffset($offset);
            echo('LL'.$maxAdjusted);
            $adjustedOffset = $this->adjustedOffsets[$maxAdjusted] + $offset - $maxAdjusted;
            $this->adjustedOffsets[$offset] = $adjustedOffset;
        }
        echo($this->adjustedOffsets[$offset]);
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
