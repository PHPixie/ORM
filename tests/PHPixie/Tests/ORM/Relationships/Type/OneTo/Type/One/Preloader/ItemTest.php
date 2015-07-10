<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type\One\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Preloader\Item
 */
class ItemTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Preloader\Result\SingleTest
{
    protected $configData = array(
        'ownerModel'       => 'fairy',
        'itemModel'        => 'flower',
        'ownerKey'         => 'fairyId',
    );
    
    protected function prepareMap()
    {
        foreach($this->entities as $id => $entity) {
            $this->method($entity, 'id', $id, array());
        }
        
        $fields = array();
        foreach($this->map as $id => $preloadId) {
            $fields[] = array(
                'id' => $preloadId,
                $this->configData['ownerKey'] => $id
            );
        }
        $this->method($this->result, 'getFields', $fields, array(array('id', $this->configData['ownerKey'])), 0);
    }

    protected function loader()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResult');
    }
    
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side\Config');
    }
    
    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side');
    }
    
    protected function getDatabaseConfig()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Config');
    }
    
    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Entity');
    }
    
    protected function preloader()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Preloader\Item(
            $this->side,
            $this->modelConfig,
            $this->result,
            $this->loader
        );
    }
    
}