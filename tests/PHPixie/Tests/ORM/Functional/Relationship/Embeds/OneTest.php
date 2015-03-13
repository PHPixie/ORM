<?php

namespace PHPixie\Tests\ORM\Functional\Relationship\Embeds;

class OneTest extends \PHPixie\Tests\ORM\Functional\Relationship\EmbedsTest
{
    protected $relationshipName = 'embedsOne';
    
    protected $itemKey = 'item';
    protected $itemProperty = 'magic';
    protected $subItemProperty = 'spell';
    
    
    public function testCreateItem()
    {
        $this->runTests('createItem');
    }
    
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
    
    protected function createItemTest()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $nature = $trixie->magic->create(array('name' => 'Nature'));
        $rain = $nature->spell->create(array('name' => 'Rain'));
        
        $this->assertSame('Nature', $trixie->magic()->name);
        $this->assertSame(true, $trixie->magic->isLoaded());
        $this->assertSame($nature, $trixie->magic());
        $this->assertSame($trixie, $nature->owner());
        $this->assertSame($this->itemProperty, $nature->ownerPropertyName());
        
        
        $this->assertSame('Rain', $nature->spell()->name);
        $this->assertSame(true, $nature->spell->isLoaded());
        $this->assertSame($rain, $nature->spell());
        $this->assertSame($nature, $rain->owner());
        $this->assertSame($this->subItemProperty, $rain->ownerPropertyName());
        
        $trixie->save();
        
        $idField = $this->idField('fairy');
        $this->assertDataAsObject('fairy', array(
            (object) array( 
                $idField => $trixie->id(),
                'name' => 'Trixie',
                'magic' => (object) array(
                    'name' => 'Nature',
                    'spell' => (object) array(
                        'name' => 'Rain'
                    )
                )
            )
        ));
    }
    
    protected function loadItemsTest()
    {
        $this->prepareEntities();
        
        $trixie = $this->orm->repository('fairy')->query()
                    ->where('name', 'Trixie')
                    ->findOne();
        
        $this->assertEquals('Nature', $trixie->magic()->name);
        $this->assertSame(true, $trixie->magic->isLoaded());
        
        $this->assertEquals('Rain', $trixie->magic()->spell()->name);
        $this->assertSame(true, $trixie->magic()->spell->isLoaded());
        
        $pixie = $this->orm->repository('fairy')->query()
                    ->where('name', 'Pixie')
                    ->findOne();
        
        $this->assertEquals('Trick', $pixie->magic()->name);
        $this->assertEquals(null, $pixie->magic()->spell());
        
        $stella = $this->orm->repository('fairy')->query()
                    ->where('name', 'Stella')
                    ->findOne();
        
        $this->assertEquals(null, $stella->magic());
    }
    
    protected function preloadItemsTest()
    {
        $map = $this->prepareEntities();
        
        $fairies = $this->orm->repository('fairy')->query()
                        ->find(array('magic.spell'))
                        ->asArray();
        
        $key = 0;
        foreach($map as $fairyName => $magicMap) {
            if($fairyName === '') {
                continue;
            }
            
            $fairy = $fairies[$key];
            $this->assertSame($fairyName, $fairy->name);
            $this->assertEquals(true, $fairy->magic->isLoaded());
            
            $magic = $fairy->magic();
            
            if(empty($magicMap)) {
                $this->assertEquals(null, $magic);
                
            }else{
                $this->assertSame(key($magicMap), $magic->name);
                $this->assertEquals(true, $magic->spell->isLoaded());
                
                $spellMap = current($magicMap);
                
                if(empty($spellMap)) {
                    $this->assertEquals(null, $magic->spell());
                    
                }else{
                    $this->assertSame(current($spellMap), $magic->spell()->name);
                }
            }
                
            $key++;
        }
    }
    
    protected function setItemTest()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $blum = $this->createEntity('fairy', array(
            'name' => 'Blum'
        ));
        
        $pixie = $this->createEntity('fairy', array(
            'name' => 'Pixie'
        ));
        
        $nature = $trixie->magic->create(array('name' => 'Nature'));
        $charm = $blum->magic->create(array('name' => 'Charm'));
        $trick = $pixie->magic->create(array('name' => 'Trick'));
        
        $love = $charm->spell->create(array('name' => 'Love'));
        
        $trick->spell->set($love);
        $trixie->magic->set($trick);
        
        $this->assertSame('Trick', $trixie->magic()->name);
        $this->assertSame($trick, $trixie->magic());
        $this->assertSame($trixie, $trick->owner());
        $this->assertSame($this->itemProperty, $trick->ownerPropertyName());
        
        $this->assertSame('Love', $trixie->magic()->spell()->name);
        $this->assertSame($trick, $love->owner());
        $this->assertSame($this->subItemProperty, $love->ownerPropertyName());

        
        $this->assertSame(null, $pixie->magic());
        $this->assertSame(null, $nature->owner());
        $this->assertSame(null, $nature->ownerPropertyName());
        
        
        $trixie->save();
        $blum->save();
        $pixie->save();
        
        $idField = $this->idField('fairy');
        $this->assertDataAsObject('fairy', array(
            (object) array( $idField => $pixie->id(), 'name' => 'Pixie'),
            (object) array( 
                $idField => $trixie->id(),
                'name' => 'Trixie',
                'magic' => (object) array(
                    'name' => 'Trick',
                    'spell' => (object) array(
                        'name' => 'Love'
                    )
                )
            ),
            (object) array(
                $idField => $blum->id(),
                'name' => 'Blum',
                'magic' => (object) array(
                    'name' => 'Charm'
                )
            )
        ));
    }
    
    protected function removeItemTest()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $blum = $this->createEntity('fairy', array(
            'name' => 'Blum'
        ));
        
        $nature = $trixie->magic->create(array('name' => 'Nature'));
        $charm = $blum->magic->create(array('name' => 'Charm'));
        
        $love = $blum->magic()->spell->create(array('name' => 'Love'));
        
        $trixie->save();
        $blum->save();
        
        $trixie = $this->orm->repository('fairy')->query()
            ->where('name', 'Trixie')
            ->findOne();
        
        $blum = $this->orm->repository('fairy')->query()
                    ->where('name', 'Blum')
                    ->findOne();
        
        $this->assertSame('Nature', $trixie->magic()->name);
        $trixie->magic->remove();
        $this->assertSame(null, $trixie->magic());

        $this->assertSame('Love', $blum->magic()->spell()->name);
        $blum->magic()->spell->remove();
        $this->assertSame(null, $blum->magic()->spell());

        $trixie->save();
        $blum->save();
        
        $idField = $this->idField('fairy');
        $this->assertDataAsObject('fairy', array(
            (object) array($idField => $trixie->id(), 'name' => 'Trixie'),
            (object) array(
                $idField => $blum->id(),
                'name' => 'Blum',
                'magic' => (object) array(
                    'name' => 'Charm'
                )
            )
        ));
    }
    
    protected function prepareEntities()
    {
        $map = array(
            'Trixie' => array(
                'Nature' => array('Rain')
            ),
            'Blum'   => array(
                'Charm' => array('Love')
            ),
            'Pixie'  => array(
                'Trick' => array()
            ),
            'Stella'  => array()
        );
        
        foreach($map as $fairyName => $magicMap) {
            
            $fairy = $this->orm->repository('fairy')->create();
            $fairy->name = $fairyName;
            
            if(!empty($magicMap)) {
                $magic = $fairy->magic->create();
                $magic->name = key($magicMap);
                
                $spellMap = current($magicMap);
                
                if(!empty($spellMap)) {
                    $spell = $magic->spell->create();
                    $spell->name = $spellMap[0];
                }
            }
            
            $fairy->save();
        }
        
        return $map;
    }
}