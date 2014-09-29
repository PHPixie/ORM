<?php

namespace PHPixieTests\ORM\Relationships\Type\Embeds\Type\Many;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Handler
 */
class HandlerTest extends \PHPixieTests\ORM\Relationships\Relationship\HandlerTest
{
    protected $ownerPropertyName = 'ownerItemsProperty';
    protected $configOnwerProperty = 'flowers';
    
    /**
     * @covers ::offsetSet
     * @covers ::<protected>
     */
    public function testOffsetSet() {
        
    }
    
    protected function prepareSetItem($item, $owner, $key)
    {
        $this->method($owner['loader'], 'cacheModel', null, array($key, $item['model']), 0);
        
        $owner['arrayNode'] = $this->prepareGetArrayNode($owner);
        $this->method($owner['arrayNode'], 'offsetSet', null, array($key, $item['document']), 0);
        $this->method($item['model'], 'setOwnerRelationship', null, array($owner['model'], $this->configOnwerProperty), 0);
        
    }
    

    protected function getPreloader() {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Preloader');
    }
    
    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Config');
    }

    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Side');
    }

    protected function getRelationship()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\EmbedsMany');
    }

    protected function getHandler()
    {
        return new \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Handler(
            $this->ormBuilder,
            $this->repositories,
            $this->planners,
            $this->plans,
            $this->steps,
            $this->loaders,
            $this->relationship,
            $this->groupMapper,
            $this->cascadeMapper
        );
    }
}