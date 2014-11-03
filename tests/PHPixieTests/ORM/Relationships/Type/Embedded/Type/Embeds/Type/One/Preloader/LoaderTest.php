<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Preloader;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Preloader\Loader
 */
class LoaderTest extends \PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Preloader\LoaderTest
{

    protected $ownerPropertyName = 'flower';

    protected function prepareOffsets()
    {
        $properties = $this->prepareOwnerProperties(16);
        $items = array();

        foreach($properties as $key => $property)
        {
            if($key == 15) {
                $this->method($property, 'exists', false, array(), 0);
                continue;
            }

            $item =  $this->getEmbeddedModel();
            $items[] = $item;

            $this->method($property, 'exists', true, array(), 0);
            $this->method($property, 'value', $item, array());
        }

        return $items;
    }

    protected function getConfig()
    {
        $config = $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Side\Config');
        $config->ownerItemProperty = $this->ownerPropertyName;
        return $config;
    }

    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Property\Model\Item');
    }

    protected function getLoader()
    {
        return new \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Loader($this->loaders, $this->config, $this->ownerLoader);
    }
}
