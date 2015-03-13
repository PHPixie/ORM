<?php

namespace PHPixie\Tests\ORM\Models\Type\Embedded;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Type\Embedded\Config
 */
class ConfigTest extends \PHPixie\Tests\ORM\Models\Model\ConfigTest
{
    protected $type = 'embedded';
    protected $driver;
    protected $defaultIdField;
    
    public function testConstruct()
    {
        parent::testConstruct();
    }
    
    protected function getConfig($slice)
    {
        return new \PHPixie\ORM\Models\Type\Embedded\Config($this->inflector, $this->model, $slice);
    }
}