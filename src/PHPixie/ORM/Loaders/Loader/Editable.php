<?php

namespace PHPixie\ORM\Loaders\Loader;

class Editable extends \PHPixie\ORM\Loaders\Loader
{
    protected $loader;
    protected $maxAllowedOffset = 0;
    protected $idOffsets = array();
    
    protected $skippedIds = array();
    protected $deletedOffsets = array();
    protected $skippedOffsets = array();
    protected $adjustedOffsets = array();
    
    protected $loaderItemsCount = null;
    
    protected $addedModels = array();
    protected $addedIdsOffsets = array();
    protected $addedOffsetsIds = array();
    
    public function __construct($loaders, $loader)
    {
        parent::__construct($loaders);
        $this->loader = $loader;
    }
    
    public function add($models)
    {
        $offset = count($this->addedModels);
        foreach ($models as $model) {
            $this->skipId($model->id());
            $this->addedModels[$offset]=$model;
            
            $id = $model->id();
            $this->addedIdsOffsets[$id]=$offset;
            $this->addedOffsetsIds[$offset]=$id;
        }
    }
    
    public function remove($models)
    {
        foreach ($models as $model) {
            $id = $model->id();
            $this->skipId($id);
            var_dump($this->addedIdsOffsets);
            if(array_key_exists($id, $this->addedIdsOffsets))
                $this->removeAddedById($id);
        }
        $this->updateSkippedOffsets();
    }
    
    protected function removeAddedById($id)
    {
        $offset = $this->addedIdsOffsets[$id];
        array_splice($this->addedOffsetsIds, $offset, 1);
        array_splice($this->addedModels, $offset, 1);
        $this->addedIdsOffsets = array_flip($this->addedOffsetsIds);
    }
    
    public function offsetExists($offset)
    {
        return $this->getByOffset($offset) !== null;
    }

    protected function assertAllowedOffset($offset)
    {
        if ($offset > $this->maxAllowedOffset)
            throw new \PHPixie\ORM\Exception\Loader("Items can only be accessed in sequential order");

        if ($offset === $this->maxAllowedOffset)
            $this->maxAllowedOffset++;
    }
    
    public function getByOffset($offset)
    {
        $this->assertAllowedOffset($offset);
        
        $loaderOffset = $this->loaderOffset($offset);
        print_r([$offset, $loaderOffset]);
        if ($this->loaderItemsCount !== null && $loaderOffset >= $this->loaderItemsCount) {
            return $this->getAddedByOffset($loaderOffset - $this->loaderItemsCount);
        }else{
            return $this->getLoadedByOffset($loaderOffset, $offset);
        }
        
    }
    
    protected function getAddedByOffset($addedOffset)
    {
        if(!array_key_exists($addedOffset, $this->addedModels))
            return null;
        
        $model = $this->addedModels[$addedOffset];
        
        if ($model->isDeleted()) {
            $this->removeAddedById($this->addedOffsetsIds[$addedOffset]);
            return $this->getAddedByOffset($addedOffset);
        }
        
        return $model;
    }
    
    protected function getLoadedByOffset($loaderOffset, $offset)
    {
        if(!$this->loader->offsetExists($loaderOffset)){
            
            if ($this->loaderItemsCount !== null)
                return null;
                
            $this->loaderItemsCount = $loaderOffset;
            return $this->getAddedByOffset(0);
        }
        
        $model = $this->loader->getByOffset($loaderOffset);
        if ($model->isDeleted()) {
            $this->deletedOffsets[] = $loaderOffset;
            return $this->updateAndGet($offset);
        }

        $id = $model->id();
        $this->idOffsets[$id] = $loaderOffset;

        if (array_key_exists($id, $this->skippedIds)) {
            $this->skippedIds[$id] = $loaderOffset;
            return $this->updateAndGet($offset);
        }

        return $model;
    }

    protected function updateAndGet($offset)
    {
        $this->updateSkippedOffsets();
        print_r([$this->adjustedOffsets, $offset]);
        return $this->getByOffset($offset);
    }

    protected function skipId($id)
    {
        if (!array_key_exists($id, $this->skippedIds)) {
            $offset = null;
            if (array_key_exists($id, $this->idOffsets)) {
                $offset = $this->idOffsets[$id];
                $this->skippedIds[$id] = $offset;
            }
        }
    }

    protected function updateSkippedOffsets()
    {
        $this->adjustedOffsets = array();
        $adjustment = 0;
        
        $skippedOffsets = array_values($this->skippedIds);
        foreach($this->deletedOffsets as $deletedOffset)
            $skippedOffsets[]=$deletedOffset;
        
        sort($skippedOffsets);
        $last = count($skippedOffsets) - 1;
        
        foreach($skippedOffsets as $key => $skippedOffset) {
            $adjustedOffset = $skippedOffset + $adjustment + 1;
            while($key === $last || ($key < $last && $skippedOffsets[$key + 1] === $adjustedOffset)){
                $key++;
                $adjustedOffset++;
            }
            $this->adjustedOffsets[$skippedOffset] = $adjustedOffset;
            $adjustment = $adjustedOffset - $skippedOffset;
        }
        
        $this->skippedOffsets = $skippedOffsets;
    }

    protected function loaderOffset($offset)
    {
        if (empty($this->adjustedOffsets))
            return $offset;
        
        return $this->getAdjustment($offset);
    }

    protected function getAdjustment($offset)
    {
        $low = 0;
        $high = count($this->skippedOffsets) - 1;
        
        while ($low <= $high) {
            $mid = (int) (($high - $low) / 2) + $low;
            
            $midOffset = $this->skippedOffsets[$mid];
            
            if ($midOffset < $offset) {
                $low = $mid + 1;
            } elseif ($midOffset > $offset) {
                $high = $mid - 1;
            } else {
                $high = $mid;
                break;
            }
        }
        
        if($high === -1)
            return $offset;
        
        $maxAdjusted = $this->skippedOffsets[$high];
        return $this->adjustedOffsets[$maxAdjusted] + $offset - $maxAdjusted;
        
    }
        
}

/*
<?php

namespace PHPixie\ORM\Loaders\Loader;

class Editable extends \PHPixie\ORM\Loaders\Loader
{
    protected $added;
    protected $loader;
    
    protected $maxAllowedOffset = 0;
    protected $idOffsets = array();
    protected $skippedIds = array();
    protected $deletedOffsets = array();
    public $skippedOffsets = array();
    protected $loaderItemsCount = null;
    
    

    public function __construct($loaders, $added, $loader)
    {
        parent::__construct($loaders);
        $this->added = $added;
        $this->loader = $loader;
    }

    public function add($models)
    {
        foreach ($models as $model) {
            $this->skipId($model->id());
            $this->added->add($model);
        }
    }

    public function remove($models)
    {
        foreach ($models as $model) {
            $id = $model->id();
            $this->skipId($id);
            $this->added->remove($id);
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
        $this->skippedIds = array();
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
            $addedOffset = $loaderOffset - $this->loaderItemsCount;
            if (!$this->added->offsetExists($addedOffset))
                return null;
            
            return $this->added->getByOffset($addedOffset)
        }
        
        if(!$this->loader->offsetExists($loaderOffset)){
            
            if ($this->loaderItemsCount === null) {
                $this->loaderItemsCount = $loaderOffset;
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

        if (array_key_exists($id, $this->skippedIds)) {
            $this->skippedIds[$id] = $loaderOffset;

            return $this->updateAndGet($offset);
        }

        return $model;
    }

    protected function updateAndGet($offset)
    {
        $this->updateSkippedOffsets();
        var_Dump($offset);
        return $this->getByOffset($offset);
    }

    protected function skipId($id)
    {
        if (!array_key_exists($id, $this->skippedIds)) {
            $offset = null;
            if (array_key_exists($id, $this->idOffsets)) {
                $offset = $this->idOffsets[$id];
                $this->skippedIds[$id] = $offset;
            }
        }
    }

    protected function updateSkippedOffsets()
    {
        $this->adjustedOffsets = array_merge(
            array_values($this->skippedIds),
            $this->deletedOffsets
        );
        
        sort($this->adjustedOffsets);
    }

    protected function loaderOffset($offset)
    {
        if (empty($this->adjustedOffsets))
            return $offset;
        
        return $offset + $this->getAdjustment($offset);
    }

    protected function getAdjustment($max)
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
                $high = $mid;
                break;
            }
        }

        return $high + 1;
    }

}
*/