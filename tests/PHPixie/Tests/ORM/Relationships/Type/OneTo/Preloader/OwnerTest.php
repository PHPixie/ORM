<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Preloader\Owner
 */
abstract class OwnerTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Preloader\Result\SingleTest
{
    protected $configData = array(
        'ownerModel'       => 'fairy',
        'itemModel'        => 'flower',
        'ownerKey'         => 'fairy_id',
        'ownerProperty'    => 'items',
        'itemProperty'     => 'fairy',
    );
    
    protected function prepareMap()
    {
        foreach($this->entities as $id => $entity) {
            if(array_key_exists($id, $this->map)) {
                $this->method($entity, 'getField', $this->map[$id], array('fairy_id'));
            }
        }
        
        $this->prepareMapIdOffsets();
    }
    
    protected function loader()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResult');
    }
    
}