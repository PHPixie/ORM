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
    protected $existingBefore = array();
    
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
            if(array_key_exists($id, $this->addedIdsOffsets))
                $this->removeAddedById($id);
        }
        $this->updateSkippedOffsets();
    }
    
    public function removeAll()
    {
        $this->loaderItemsCount = 0;
        $this->idOffsets = array();
        $this->skippedIds = array();
        $this->deletedOffsets = array();
        
        $this->skippedOffsets = array();
        $this->existingBefore = array();
        
        $this->addedModels = array();
        $this->addedIdsOffsets = array();
        $this->addedOffsetsIds = array();
        
        $this->loader = null;
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

        print_r([$id, $this->skippedIds]);
        if (array_key_exists($id, $this->skippedIds)) {
            $this->skippedIds[$id] = $loaderOffset;
            return $this->updateAndGet($offset);
        }

        return $model;
    }

    protected function updateAndGet($offset)
    {
        $this->updateSkippedOffsets();
        echo($offset);
        return $this->getByOffset($offset);
    }

    protected function skipId($id)
    {
        if (!array_key_exists($id, $this->skippedIds)) {
            $offset = null;
            if (array_key_exists($id, $this->idOffsets)) {
                $offset = $this->idOffsets[$id];
            }
            $this->skippedIds[$id] = $offset;
        }
    }

    protected function updateSkippedOffsets()
    {
        $skippedOffsets = array();
        foreach($this->skippedIds as $skippedOffset)
            if($skippedOffset!==null)
                $skippedOffsets[]=$skippedOffset;
            
        foreach($this->deletedOffsets as $deletedOffset)
            $skippedOffsets[]=$deletedOffset;
        $skippedOffsets = array_unique($skippedOffsets);
        sort($skippedOffsets);
        $count = count($skippedOffsets);
        
        $this->existingBefore = array();
        foreach($skippedOffsets as $key => $skippedOffset) {
            if($key === 0) {
                $this->existingBefore[] = $skippedOffset;
            }else{
                $this->existingBefore[] = $this->existingBefore[$key-1] + $skippedOffset - $skippedOffsets[$key-1] - 1;
            }
        }
        
        $this->skippedOffsets = $skippedOffsets;
        print_r([$this->skippedOffsets, $this->existingBefore]);
    }

    protected function loaderOffset($offset)
    {
        if (empty($this->skippedOffsets))
            return $offset;
        
        return $this->getAdjustment($offset);
    }

    protected function getAdjustment($offset)
    {
        $low = 0;
        $count = count($this->existingBefore);
        $high =  $count - 1;
        $result = -1;
        
        while ($low <= $high) {
            $mid = (int) (($high - $low) / 2) + $low;
            
            $midOffset = $this->existingBefore[$mid];
            
            if ($midOffset <= $offset) {
                $low = $mid + 1;
            } elseif ($midOffset > $offset) {
                $high = $mid - 1;
            }
        }
        
        if($low === 0) {
            $adjusted = $offset;
        }else{
            $adjusted = $this->skippedOffsets[$low-1] + $offset - $this->existingBefore[$low-1] + 1;
        }
        
        print_r([$offset, $adjusted]);
        return $adjusted;
        
    }
        
}