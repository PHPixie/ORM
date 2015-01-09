<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\One\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Preloader\Item
 */
class ItemTest extends \PHPixieTests\ORM\Relationships\Relationship\Implementation\Preloader\Result\SingleTest
{
    protected $configData = array(
        'ownerModel'       => 'fairy',
        'itemModel'        => 'flower',
        'ownerKey'         => 'fairy_id',
    );
    
    protected function prepareMap()
    {
        foreach($this->entities as $id => $entity) {
            $this->method($entity, 'id', $id, array());
        }
        
        $repository = $this->getDatabaseRepository();
        $this->method($this->loader, 'repository', $repository, array(), 0);
        
        $loaderResult = $this->getReusableResult();
        $this->method($this->loader, 'reusableResult', $loaderResult, array(), 1);
        
        $config = $this->getDatabaseConfig();
        $config->idField = 'id';
        
        $this->method($repository, 'config', $config, array(), 0);
        
        $fields = array();
        foreach($this->map as $id => $preloadId) {
            $fields[] = array(
                'id' => $preloadId,
                $this->configData['ownerKey'] => $id
            );
        }
        $this->method($loaderResult, 'getFields', $fields, array(array('id', $this->configData['ownerKey'])), 0);
    }
    
    protected function getModel()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Database\Model');
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
            $this->loader
        );
    }
    
}