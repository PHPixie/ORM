<?php

namespace PHPixie\Tests\ORM\Functional;

abstract class RelationshipTest extends \PHPixie\Tests\ORM\Functional\Testcase
{
    protected $defaultORMConfig;
    
    protected function prepareOrm($config = array())
    {
        $this->ormConfigData = array_merge($this->defaultORMConfig, $config);
        $this->orm = $this->orm();
    }
    
}