<?php

namespace PHPixieTests\ORM\Functional\Relationship\OneTo;

class OneTest extends \PHPixieTests\ORM\Functional\Relationship\OneToTest
{
    protected $relationshipName = 'oneToOne';
    protected $itemKey = 'item';
    protected $itemProperty = 'flower';
    
    public function testSetItem()
    {
        $this->runTests('setItem');
    }
    
    public function testRemoveItem()
    {
        $this->runTests('removeItem');
    }
    
    public function testLoadItems()
    {
        $this->runTests('loadItems');
    }
    
    public function testPreloadItems()
    {
        $this->runTests('preloadItems');
    }
    
    protected function setItemTest()
    {
        $red = $this->createEntity('flower', array(
            'name' => 'Red'
        ));
        
        $green = $this->createEntity('flower', array(
            'name' => 'Green'
        ));
        
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        
        $trixie->flower->set($red);
        $this->assertSame($red, $trixie->flower());
        $this->assertSame($trixie, $red->fairy());
        
        $trixie->flower->set($green);
        $this->assertSame($green, $trixie->flower());
        $this->assertSame($trixie, $green->fairy());
        $this->assertSame(null, $red->fairy());

        $this->assertData('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => null),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => 1),
        ));
    }
    
    protected function removeItemTest()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $red = $this->createEntity('flower', array(
            'name' => 'Red'
        ));
        
        $trixie->flower->set($red);
        $trixie->flower->remove();
        
        $this->assertSame(null, $red->fairy());
        $this->assertSame(null, $trixie->flower());
        
        $this->assertData('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => null),
        ));
    }
    
    protected function loadItemsTest()
    {
        $this->prepareEntities();
        
        $trixie = $this->orm->get('fairy')->query()
                    ->where('name', 'Trixie')
                    ->findOne();
        
        $this->assertEquals('Red', $trixie->flower()->name);
        
        $pixie = $this->orm->get('fairy')->query()
                    ->where('name', 'Pixie')
                    ->findOne();
        
        $this->assertEquals(null, $pixie->flower());
    }
    
    protected function preloadItemsTest()
    {
        $map = $this->prepareEntities();
        
        $fairies = $this->orm->get('fairy')->query()
                        ->find(array('flower'))
                        ->asArray();
        
        $key = 0;
        foreach($map as $fairyName => $flowerName) {
            if($fairyName === '') {
                continue;
            }
                
            $fairy = $fairies[$key];
            $this->assertSame($fairyName, $fairy->name);
            $this->assertEquals(true, $fairy->flower->isLoaded());
            
            if(empty($flowerName)) {
                $this->assertEquals(null, $fairy->flower());
            }else{
                $this->assertSame($flowerName[0], $fairy->flower()->name);
            }
            $key++;
        }
    }
    
    protected function prepareEntities($addWithoutOwner = true)
    {
        $map = array(
            'Trixie' => array('Red'),
            'Blum'   => array('Yellow'),
            'Pixie'  => array()
        );
        
        if($addWithoutOwner) {
            $map[''] = array('Purple');
        }
        
        foreach($map as $fairyName => $flowerName) {
            
            if($fairyName !== '') {
                $fairy = $this->createEntity('fairy', array(
                    'name' => $fairyName
                ));
            }
            
            if(!empty($flowerName)) {
                
                $flower = $this->createEntity('flower', array(
                    'name' => $flowerName[0]
                ));
                
                if($fairyName !== '') {
                    $fairy->flower->set($flower);
                }
            }
        }
        
        return $map;
    }
}