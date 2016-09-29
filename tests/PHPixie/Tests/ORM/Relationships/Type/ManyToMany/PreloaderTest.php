<?php

namespace PHPixie\Tests\ORM\Relationships\Type\ManyToMany;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\ManyToMany\Preloader
 */
class PreloaderTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Preloader\Result\Multiple\IdMapTest
{
    protected $pivotResult;
    
    protected $configData = array(
        'leftModel'     => 'fairy',
        'leftProperty'  => 'flowers',
        'leftPivotKey'  => 'fairyId',
        'rightModel'    => 'flower',
        'rightProperty' => 'fairies',
        'rightPivotKey' => 'flowerId',
        'pivot'         => 'fairies_flowers'
    );
    
    public function setUp()
    {
        $this->pivotResult = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
        parent::setUp();
    }
    
    protected function prepareMap($type = 'left')
    {
        foreach($this->entities as $id => $entity) {
            $this->method($entity, 'id', $id, array());
        }
        
        $this->method($this->side, 'type', $type, array(), 0);
        
        $opposing = $type === 'left' ? 'right' : 'left';
        
        $ownerKey = $this->configData[$opposing.'PivotKey'];
        $itemKey = $this->configData[$type.'PivotKey'];
        
        $fields = array();
        $allIds = [];
        foreach($this->map as $ownerId => $ids) {
            foreach($ids as $id) {
                $allIds[$id] = true;
                $fields[] = array(
                                $ownerKey => $ownerId,
                                $itemKey  => $id,
                            );
            }
        }
        
        $this->method($this->pivotResult, 'getFields', $fields, array(array($ownerKey, $itemKey)), 0);
        $this->method($this->result, 'getField', array_keys($allIds), array('id'), 0);
        $this->prepareMapIdOffsets(1);
        
    }

    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Property\Entity');
    }
    
    protected function relationship()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany');
    }
    
    protected function loader()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResult');
    }
    
    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Side');
    }
    
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Side\Config');
    }
    
    protected function preloader()
    {
        return new \PHPixie\ORM\Relationships\Type\ManyToMany\Preloader(
            $this->loaders,
            $this->side,
            $this->modelConfig,
            $this->result,
            $this->loader,
            $this->pivotResult
        );
    }
}