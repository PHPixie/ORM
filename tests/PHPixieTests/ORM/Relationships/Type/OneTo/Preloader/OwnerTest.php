<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Preloader\Owner
 */
abstract class OwnerTest extends \PHPixieTests\ORM\Relationships\Relationship\Implementation\Preloader\Result\SingleTest
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
        foreach($this->entities as $id => $model) {
            $this->method($model, 'getField', $this->map[$id], array('fairy_id'));
        }
        
        $this->prepareMapIdOffsets();
    }
    
    protected function loader()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResult');
    }
    
}