<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds\Type\Many;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Preloader
 */
class PreloaderTest extends \PHPixie\Tests\ORM\Relationships\Type\Embeds\PreloaderTest
{
    
    protected function prepareGetEntities($property, $isEmpty = false)
    {
        $loader = $this->getArrayNodeLoader();
        
        $entities = array();
        if(!$isEmpty) {
            $entities[] = $this->getEntity();
            $entities[] = $this->getEntity();
        }
        
        $this->method($property, 'value', $loader, array(), 0);
        $this->method($loader, 'asArray', $entities, array(), 0);
        
        return $entities;
    }
    
    protected function getArrayNodeLoader()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Embedded\ArrayNode');
    }
    
    protected function preloader()
    {
        return new \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Preloader();
    }
}