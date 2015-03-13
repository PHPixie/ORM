<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Preloader
 */
abstract class PreloaderTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\PreloaderTest
{
    protected $preloader;
    
    protected $preloaders;
    
    public function setUp()
    {
        $this->preloaders = array(
            'pixie' => $this->quickMock('\PHPixie\ORM\Relationships\Relationship\Preloader'),
            'fairy' => $this->quickMock('\PHPixie\ORM\Relationships\Relationship\Preloader'),
        );
        
        parent::setUp();
    }
    
    /**
     * @covers ::addPreloader
     * @covers ::loadProperty
     * @covers ::<protected>
     */
    public function testLoadProperty()
    {
        $property = $this->getProperty();
        
        foreach($this->preloaders as $relationship => $preloader) {
            $this->preloader->addPreloader($relationship, $preloader);
        }
        
        $entities = $this->prepareGetEntities($property);
        
        foreach($entities as $key => $entity) {
            $at = 0;
            foreach($this->preloaders as $relationship => $preloader) {
                $entityProperty = $this->getProperty();
                $this->method($entity, 'getRelationshipProperty', $entityProperty, array($relationship), $at++, true);
                $this->method($preloader, 'loadProperty', null, array($entityProperty), $key);
            }
        }
        
        $this->preloader->loadProperty($property);
    }
    
    protected function getEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Model\Entity');
    }
    
    protected function getProperty()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Property\Entity');
    }
    
    abstract protected function prepareGetEntities($property, $isEmpty = false);
}
