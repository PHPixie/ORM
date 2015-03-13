<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds\Type\One;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\One\Preloader
 */
class PreloaderTest extends \PHPixie\Tests\ORM\Relationships\Type\Embeds\PreloaderTest
{
    /**
     * @covers ::loadProperty
     * @covers ::<protected>
     */
    public function testLoadPropertyNull()
    {
        $property = $this->getProperty();
        
        foreach($this->preloaders as $relationship => $preloader) {
            $this->preloader->addPreloader($relationship, $preloader);
        }
        
        $entities = $this->prepareGetEntities($property, true);
        $this->preloader->loadProperty($property);
    }
    
    protected function prepareGetEntities($property, $isEmpty = false)
    {
        $value = null;
        if(!$isEmpty) {
            $value = $this->getEntity();
        }
        
        $this->method($property, 'value', $value, array());
        
        if($isEmpty) {
            return array();
        }
        
        return array($value);
    }
    
    protected function preloader()
    {
        return new \PHPixie\ORM\Relationships\Type\Embeds\Type\One\Preloader();
    }
}