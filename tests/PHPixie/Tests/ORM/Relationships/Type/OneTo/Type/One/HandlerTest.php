<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type\One;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Handler
 */
class HandlerTest extends \PHPixie\Tests\ORM\Relationships\Type\OneTo\HandlerTest
{
    protected $itemSide = 'item';
    protected $ownerPropertyName = 'ownerItemProperty';
    protected $configOwnerProperty = 'flower';
    
   /**
     * @covers ::loadProperty
     * @covers ::<protected>
     */
    public function testLoadProperty()
    {
        $this->loadPropertyTest(true);
        $this->loadPropertyTest(false, 'owner');
        $this->loadPropertyTest(false, 'item');
    }
    
    /**
     * @covers ::linkPlan
     * @covers ::<protected>
     */
    public function testLinkPlan()
    {   
        $owner = $this->getDatabaseEntity();
        $items = $this->getDatabaseEntity();
        $this->prepareRepositories();
        
        $unlinkPlan = $this->prepareUnlinkTest(true, $owner, true, $items, 'or');
        $linkPlan = $this->prepareLinkPlan(
            $owner,
            $items,
            $plansOffset = 1,
            $stepsOffset = 1,
            $inPlannerOffset = 2,
            $ownerRepoOffset = 0,
            $itemRepoOffset = 1
        );
        
        $plan = $this->getPlan();
        $this->method($this->plans, 'steps', $plan, array(), 2);
        
        $this->method($plan, 'appendPlan', null, array($unlinkPlan), 0);
        $this->method($plan, 'appendPlan', null, array($linkPlan), 1);
        
        $this->assertSame($plan, $this->handler->linkPlan($this->propertyConfig, $owner, $items));
    }
    
    /**
     * @covers ::unlinkPlan
     * @covers ::<protected>
     */
    public function testUnlinkPlan()
    {   
        foreach(array('owner', 'item') as $type) {
            $items = $this->getDatabaseEntity();
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
    
    protected function loadPropertyTest($isNull = false, $type = 'owner')
    {
        $side = $this->side($type, $this->configData);
        
        $related = $this->getSideEntity($this->opposing($type));
        
        if($isNull) {
            $this->prepareLoadSingleProperty($side, $related['entity'], null);
            $this->expectSetValue($related, null);
            
        }else {
            $value   = $this->getSideEntity($type);
            
            $this->prepareLoadSingleProperty($side, $related['entity'], $value['entity']);
            $this->expectSetValue($value, $related);
            $this->expectSetValue($related, $value);
        }
        
        $this->handler->loadProperty($side, $related['entity']);
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
                        $related = $this->getSideEntity($opposing);
                        $this->expectSetValue($related, null);
                    }
                }
                
                $mocks[$side] = $this->getSideEntity($side, true, true, ${$side.'Loaded'}, $related);
                $params[$side] = $mocks[$side]['entity'];    
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
            $related = $this->getSideEntity($this->opposing($type), true, true, $relatedIsLoaded);
            if($relatedIsLoaded)
                $this->expectSetValue($related, null);
        }
        $mock = $this->getSideEntity($type, true, true, $isLoaded, $related);
        $side = $this->side($this->opposing($type), $this->configData);
        $this->handler->unlinkProperties($side, $mock['entity']);
    }
    
    protected function resetPropertiesTest($type)
    {
        $side = $this->side($this->opposing($type), $this->configData);
        
        $mock = $this->getSideEntity($type, true);
        $this->expectsExactly($mock['property'], 'reset', 1);
        $this->handler->resetProperties($side, $mock['entity']);
        
        $related = $this->getSideEntity($this->opposing($type));
        $items = array(
            $this->getSideEntity($type, true),
            $this->getSideEntity($type, true, true),
            $this->getSideEntity($type, true, true, true, null),
            $this->getSideEntity($type, true, true, true, $related),
        );

        $entities = array();
        foreach($items as $item){
            $this->expectsExactly($item['property'], 'reset', 1);
            $entities[]=$item['entity'];
        }
        
        $this->expectSetValue($related, null);
        
        $this->handler->resetProperties($side, $entities);
    }
    
    protected function getSideEntity($type, $expectCreateMissing = true, $hasProperty = true, $valueLoaded = false, $value = null) {
        $valueEntity = $value !== null ? $value['entity'] : null;
        $entity = $this->getDatabaseEntity();
        return $this->addSingleProperty($entity, $this->opposing($type), $hasProperty, $valueLoaded, $valueEntity, $expectCreateMissing);
    }
    
    protected function opposing($type)
    {
        return $type === 'owner' ? 'item' : 'owner';
    }
    
    protected function getSingleProperty($type)
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Entity');
    }

    protected function getPreloader($type)
    {
        if($type !== 'owner')
            $type = 'items';

        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Preloader\\'.ucfirst($type));
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
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One');
    }

    protected function getHandler()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Handler(
            $this->models,
            $this->planners,
            $this->plans,
            $this->steps,
            $this->loaders,
            $this->mappers,
            $this->relationship
        );
    }
}
