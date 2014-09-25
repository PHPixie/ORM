<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\One;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Handler
 */
class HandlerTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\HandlerTest
{
    protected $itemSide = 'item';
    protected $ownerPropertyName = 'ownerItemProperty';
    protected $configOnwerProperty = 'flower';
    
   /**
     * @covers ::loadProperty
     * @covers ::<protected>
     */
    public function testLoadProperty()
    {
        foreach(array('owner', 'item') as $type) {
            $side = $this->side($type, $this->configData);
            $related = $this->getDatabaseModel();
            $value = $this->prepareLoadSingleProperty($side, $related);
            $this->assertSame($value, $this->handler->loadProperty($side, $related));
        }
    }
     
    /**
     * @covers ::linkPlan
     * @covers ::<protected>
     */
    public function testLinkPlan()
    {   
        $owner = $this->getDatabaseModel();
        $items = $this->getDatabaseModel();
        $this->prepareRepositories();
        
        $plan = $this->prepareUnlinkTest(true, $owner, true, $items, 'or');
        $linkPlan = $this->prepareLinkPlan($owner, $items, $plansOffset = 1, $ownerRepoOffset = 2, $itemRepoOffset= 3, $plannersOffset = 4);
        $this->method($plan, 'appendPlan', null, array($linkPlan), 0);
        $this->assertSame($plan, $this->handler->linkPlan($this->propertyConfig, $owner, $items));
    }
    
    /**
     * @covers ::unlinkPlan
     * @covers ::<protected>
     */
    public function testUnlinkPlan()
    {   
        foreach(array('owner', 'item') as $type) {
            $items = $this->getDatabaseModel();
            $this->prepareRepositories();
            if($type === 'owner'){
                $plan = $this->prepareUnlinkTest(true, $items, false, null);
            }else{
                $plan = $this->prepareUnlinkTest(false, null, true, $items);
            }
            
            $side = $this->side($this->opposing($type), $this->configData);
            $this->assertSame($plan, $this->handler->unlinkPlan($side, $items));
        }
        
    }

    /**
     * @covers ::linkProperties
     * @covers ::<protected>
     */
    public function testLinkProperties()
    {
        $this->linkPropertiesTest();
        $this->linkPropertiesTest(false, false, true);
        $this->linkPropertiesTest(false, false, true, true);
        $this->linkPropertiesTest(false, false, false, true);
        $this->linkPropertiesTest(false, false, true, true, true, true);
        
        $this->linkPropertiesTest(true, false, false, true);
        $this->linkPropertiesTest(false, true, true, false);
        $this->linkPropertiesTest(false, true, false, false);
    }
    
    /**
     * @covers ::unlinkProperties
     * @covers ::<protected>
     */
    public function testUnlinkProperties()
    {
        $this->unlinkPropertiesTest('owner');
        $this->unlinkPropertiesTest('owner', true);
        $this->unlinkPropertiesTest('owner', true, true);
        $this->unlinkPropertiesTest('item');
        $this->unlinkPropertiesTest('item', true);
        $this->unlinkPropertiesTest('item', true, true);
    }
    
    /**
     * @covers ::resetProperties
     * @covers ::<protected>
     */
    public function testResetProperties()
    {
        $this->resetPropertiesTest('owner');
        $this->resetPropertiesTest('item');
    }
    
    protected function linkPropertiesTest($ownerIsQuery = false, $itemIsQuery = false, $ownerLoaded = false,  $itemLoaded = false, $ownerValueNull = false, $itemValueNull = false)
    {
        $mocks = array();
        $params = array();
        $sides = array('item' => 'owner', 'owner' => 'item');
        
        foreach($sides as $side => $opposing) {
            if(!${$side.'IsQuery'}) {
                $related = null;
                if(${$side.'Loaded'}){
                    if(!${$side.'ValueNull'}) {
                        $related = $this->getProperty($opposing);
                        $this->expectSetValue($related, null);
                    }
                }
                
                $mocks[$side] = $this->getProperty($side, true, ${$side.'Loaded'}, $related);
                $params[$side] = $mocks[$side]['model'];    
            }else{
                $params[$side] = $this->getQuery();
            }
        }
        
        foreach($mocks as $side => $mock) {
            $opposing = $sides[$side];
            if(!${$opposing.'IsQuery'}){
                $this->expectSetValue($mock, $mocks[$opposing]);
            }else{
                $this->expectsExactly($mock['property'], 'reset', 1);
            }
        }
        
        $this->handler->linkProperties($this->propertyConfig, $params['owner'], $params['item']);
    }
    
    protected function unlinkPropertiesTest($type, $isLoaded = false, $relatedIsLoaded = false)
    {
        $related = null;
        if($isLoaded) {
            $related = $this->getProperty($this->opposing($type), true, $relatedIsLoaded);
            if($relatedIsLoaded)
                $this->expectSetValue($related, null);
        }
        $mock = $this->getProperty($type, true, $isLoaded, $related);
        $side = $this->side($this->opposing($type), $this->configData);
        $this->handler->unlinkProperties($side, $mock['model']);
    }
    
    protected function resetPropertiesTest($type)
    {
        $side = $this->side($this->opposing($type), $this->configData);
        
        $mock = $this->getProperty($type);
        $this->expectsExactly($mock['property'], 'reset', 1);
        $this->handler->resetProperties($side, $mock['model']);
        
        $related = $this->getProperty($this->opposing($type));
        $items = array(
            $this->getProperty($type),
            $this->getProperty($type, true),
            $this->getProperty($type, true, true, null),
            $this->getProperty($type, true, true, $related),
        );

        $models = array();
        foreach($items as $item){
            $this->expectsExactly($item['property'], 'reset', 1);
            $models[]=$item['model'];
        }
        
        $this->expectSetValue($related, null);
        
        $this->handler->resetProperties($side, $models);
    }
    
    protected function getProperty($side, $hasProperty = true, $ownerLoaded = false, $value = null) {
        $property = $side === 'owner' ? 'item' : 'owner';
        $model = $this->getDatabaseModel();
        return $this->addSingleProperty($model, $side, $hasProperty, $ownerLoaded, $value['model']);
    }
    
    protected function opposing($type)
    {
        return $type === 'owner' ? 'item' : 'owner';
    }
    
    protected function getSingleProperty($type)
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Model');
    }
    
    protected function getPreloader($type)
    {
    
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Preloader');
    }

    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side\Config');
    }

    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side');
    }

    protected function getRelationship()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneToMany');
    }

    protected function getHandler()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Handler(
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
