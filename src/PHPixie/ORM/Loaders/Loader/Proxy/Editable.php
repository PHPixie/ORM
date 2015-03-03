<?php

namespace PHPixie\ORM\Loaders\Loader\Proxy;

class Editable extends \PHPixie\ORM\Loaders\Loader\Proxy
{
    protected $maxAccessedOffset = -1;
    protected $idOffsets = array();
    
    protected $skippedIds = array();
    protected $deletedOffsets = array();
    
    protected $skippedOffsets = array();
    protected $existingBefore = array();
    
    protected $loaderItemsCount = null;
    
    protected $addedEntities = array();
    protected $addedIdsOffsets = array();
    protected $addedOffsetsIds = array();
    
    public function add($entities)
    {
        $offset = count($this->addedEntities);
        foreach ($entities as $entity) {
            $id = $entity->id();
            if(array_key_exists($id, $this->addedIdsOffsets)) {
                $offset = $this->addedIdsOffsets[$id];
                $this->addedEntities[$offset]=$entity;    
            }else{
                $this->skipId($id);
                $this->addedEntities[$offset]=$entity;
            
                $this->addedIdsOffsets[$id]=$offset;
                $this->addedOffsetsIds[$offset]=$id;
            }
        }
    }
    
    public function remove($entities)
    {
        foreach ($entities as $entity) {
            $id = $entity->id();
            $this->skipId($id);
            if(array_key_exists($id, $this->addedIdsOffsets)) {
                $offset = $this->addedIdsOffsets[$id];
                if($offset <= $this->maxAccessedOffset) {
                    $this->maxAccessedOffset--;
                }
                $this->removeAddedById($id);
            }
        }
        $this->updateSkippedOffsets();
    }
    
    public function accessedEntities()
    {
        $entities = array();
        for($i=0; $i<=$this->maxAccessedOffset; $i++)
            $entities[]=$this->getByOffset($i);
        return $entities;
    }
    
    public function removeAll()
    {
        $this->loaderItemsCount = 0;
        $this->maxAccessedOffset = -1;
        $this->idOffsets = array();
        $this->skippedIds = array();
        $this->deletedOffsets = array();
        
        $this->skippedOffsets = array();
        $this->existingBefore = array();
        
        $this->addedEntities = array();
        $this->addedIdsOffsets = array();
        $this->addedOffsetsIds = array();
        
        $this->loader = null;
    }
    
    protected function removeAddedById($id)
    {
        $offset = $this->addedIdsOffsets[$id];
        array_splice($this->addedOffsetsIds, $offset, 1);
        array_splice($this->addedEntities, $offset, 1);
        $this->addedIdsOffsets = array_flip($this->addedOffsetsIds);
    }
    
    public function offsetExists($offset)
    {
        return $this->getEntityByOffset($offset) !== null;
    }

    public function getByOffset($offset)
    {
        $entity = $this->getEntityByOffset($offset);
        if($entity === null)
            throw new \PHPixie\ORM\Exception\Loader("Offset $offset does not exist.");
        
        return $entity;
    }
    
    protected function getEntityByOffset($offset)
    {
        $this->assertAllowedOffset($offset);
        $loaderOffset = $this->loaderOffset($offset);
        
        if ($this->loaderItemsCount !== null && $loaderOffset >= $this->loaderItemsCount) {
            $entity = $this->getAddedByOffset($loaderOffset - $this->loaderItemsCount);
        }else{
            $entity = $this->getLoadedByOffset($loaderOffset, $offset);
        }

        if ($entity !== null && $offset === $this->maxAccessedOffset+1)
            $this->maxAccessedOffset++;
        
        return $entity;
    }

    protected function assertAllowedOffset($offset)
    {
        if ($offset > $this->maxAccessedOffset+1)
            throw new \PHPixie\ORM\Exception\Loader("Items can only be accessed in sequential order");
    }
    
    protected function getAddedByOffset($addedOffset)
    {
        if(!array_key_exists($addedOffset, $this->addedEntities))
            return null;
        
        $entity = $this->addedEntities[$addedOffset];
        
        if ($entity->isDeleted()) {
            $this->removeAddedById($this->addedOffsetsIds[$addedOffset]);
            return $this->getAddedByOffset($addedOffset);
        }
        
        return $entity;
    }
    
    protected function getLoadedByOffset($loaderOffset, $offset)
    {
        if(!$this->loader->offsetExists($loaderOffset)){
            
            if ($this->loaderItemsCount !== null)
                return null;
                
            $this->loaderItemsCount = $loaderOffset;
            return $this->getAddedByOffset(0);
        }
        
        $entity = $this->loader->getByOffset($loaderOffset);
        if ($entity->isDeleted()) {
            $this->deletedOffsets[] = $loaderOffset;
            return $this->updateAndGet($offset);
        }

        $id = $entity->id();
        $this->idOffsets[$id] = $loaderOffset;

        if (array_key_exists($id, $this->skippedIds)) {
            $this->skippedIds[$id] = $loaderOffset;
            return $this->updateAndGet($offset);
        }

        return $entity;
    }

    protected function updateAndGet($offset)
    {
        $this->updateSkippedOffsets();
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
        
        return $adjusted;
        
    }
        
}