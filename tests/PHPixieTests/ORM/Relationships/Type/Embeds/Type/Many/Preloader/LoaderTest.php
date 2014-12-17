<?php

namespace PHPixieTests\ORM\Relationships\Type\Embeds\Type\Many\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Preloader\Loader
 */
class LoaderTest extends \PHPixieTests\ORM\Relationships\Type\Embeds\Preloader\LoaderTest
{

    protected $ownerPropertyName = 'flowers';

    protected function prepareOffsets()
    {
        $properties = $this->prepareOwnerProperties(5);
        $items = array();

        foreach($properties as $property)
        {
            $propertyItems = array();
            for($i=0; $i<3; $i++) {
                $propertyItems[$i] = $this->getEmbeddedModel();
                $items[] = $propertyItems[$i];
            }

            $this->method($property, 'count', 3, array(), 0);
            $this->method($property, 'offsetGet', $this->itemsCallback($propertyItems));
        }

        return $items;
    }

    protected function itemsCallback($items)
    {
        return function($i) use($items) {
            return $items[$i];
        };
    }

    protected function getConfig()
    {
        $config = $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Side\Config');
        $config->ownerItemsProperty = $this->ownerPropertyName;
        return $config;
    }

    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Property\Model\Items');
    }

    protected function getLoader()
    {
        return new \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Loader($this->loaders, $this->config, $this->ownerLoader);
    }
}
