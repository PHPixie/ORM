<?php

namespace PHPixie\Tests\ORM\Functional\Relationship\Embeds;

class ManyTest extends \PHPixie\Tests\ORM\Functional\Relationship\EmbedsTest
{
    protected $relationshipName = 'embedsMany';
    
    protected $itemKey = 'items';
    protected $itemProperty = 'magics';
    protected $subItemProperty = 'spells';
    
    public function testCreateItem()
    {
        $this->runTests('createItem');
    }
    
    public function testAddItem()
    {
        $this->runTests('addItem');
    }
    
    public function testRemoveItem()
    {
        $this->runTests('removeItem');
    }

    public function testAddToEmpty()
    {
        $this->runTests('addToEmpty');
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
        
        $nature = $trixie->magics->create(array('name' => 'Nature'));
        $rain = $nature->spells->create(array('name' => 'Rain'));
        
        $animal = $trixie->magics->create(array('name' => 'Animal'));
        
        $this->assertSame(true, $trixie->magics->isLoaded());
        $this->assertSame('Nature', $trixie->magics[0]->name);
        $this->assertSame('Animal', $trixie->magics[1]->name);
        $this->assertSame(2, $trixie->magics->count());
        
        $this->assertSame($nature, $trixie->magics[0]);
        $this->assertSame($trixie, $nature->owner());
        $this->assertSame($this->itemProperty, $nature->ownerPropertyName());
        
        
        $this->assertSame(true, $nature->spells->isLoaded());
        $this->assertSame('Rain', $nature->spells[0]->name);
        $this->assertSame($rain, $nature->spells[0]);
        $this->assertSame($nature, $rain->owner());
        $this->assertSame($this->subItemProperty, $rain->ownerPropertyName());
        
        $trixie->save();
        
        $idField = $this->idField('fairy');
        $this->assertDataAsObject('fairy', array(
            (object) array( 
                $idField => $trixie->id(),
                'name' => 'Trixie',
                'magics' => array(
                    (object) array(
                        'name' => 'Nature',
                        'spells' => array(
                            (object) array(
                                'name' => 'Rain'
                            )
                        )
                    ),
                    (object) array(
                        'name' => 'Animal',
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
        
        $magics = $trixie->magics;
        
        $this->assertEquals('Nature', $magics[0]->name);
        $this->assertEquals('Animal', $magics[1]->name);
        $this->assertEquals(2, $trixie->magics->count());
        
        $this->assertSame(true, $trixie->magics->isLoaded());
        
        $spells = $magics[0]->spells;
        
        $this->assertEquals('Rain', $spells[0]->name);
        $this->assertEquals('Wind', $spells[1]->name);
        $this->assertEquals(2, $magics->count());
        
        $this->assertSame(true, $magics[0]->spells->isLoaded());
        
        
        $pixie = $this->orm->repository('fairy')->query()
                    ->where('name', 'Pixie')
                    ->findOne();
        
        $magics = $pixie->magics;
        
        $this->assertEquals('Trick', $magics[0]->name);
        $this->assertEquals(1, $pixie->magics->count());
        
        $this->assertEquals(0, $magics[0]->spells->count());
        
        $stella = $this->orm->repository('fairy')->query()
                    ->where('name', 'Stella')
                    ->findOne();
        
        $this->assertEquals(0, $stella->magics->count());
    }
    
    protected function preloadItemsTest()
    {
        $map = $this->prepareEntities();
        
        $fairies = $this->orm->repository('fairy')->query()
                        ->find(array('magics.spells'))
                        ->asArray();
        
        $key = 0;
        foreach($map as $fairyName => $magicMap) {
            if($fairyName === '') {
                continue;
            }
            
            $fairy = $fairies[$key];
            $this->assertSame($fairyName, $fairy->name);
            
            $magics = $fairy->magics;
            $this->assertEquals(true, $magics->isLoaded());
            $this->assertEquals(count($magicMap), $magics->count());
            
            $magicKey = 0;
            foreach($magicMap as $magicName => $spellMap) {
                $this->assertEquals($magicName, $magics[$magicKey]->name);
                
                $spells = $magics[$magicKey]->spells;
                $this->assertEquals(true, $spells->isLoaded());
                $this->assertEquals(count($spellMap), $spells->count());
                

                
                foreach($spellMap as $spellKey => $spellName) {
                    $this->assertEquals($spellName, $spells[$spellKey]->name);
                }
                
                $magicKey++;
            }
            $key++;
        }
    }
    
    protected function addItemTest()
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
        
        $nature = $trixie->magics->create(array('name' => 'Nature'));
        $charm = $blum->magics->create(array('name' => 'Charm'));
        $trick = $pixie->magics->create(array('name' => 'Trick'));
        
        $love = $charm->spells->create(array('name' => 'Love'));
        
        $trick->spells->add($love);
        $trixie->magics->add($trick);
        
        $this->assertSame('Nature', $trixie->magics[0]->name);
        $this->assertSame('Trick', $trixie->magics[1]->name);
        $this->assertSame($trick, $trixie->magics[1]);
        $this->assertSame(2, $trixie->magics->count());
        $this->assertSame($trixie, $trick->owner());
        $this->assertSame($this->itemProperty, $trick->ownerPropertyName());
        
        $this->assertSame('Love', $trixie->magics[1]->spells[0]->name);
        $this->assertSame($trick, $love->owner());
        $this->assertSame($this->subItemProperty, $love->ownerPropertyName());

        
        $this->assertSame(0, $pixie->magics->count());
        $this->assertSame(1, $blum->magics->count());
        
        $trixie->save();
        $blum->save();
        $pixie->save();
        
        $idField = $this->idField('fairy');
        $this->assertDataAsObject('fairy', array(
            (object) array( $idField => $pixie->id(), 'name' => 'Pixie'),
            (object) array( 
                $idField => $trixie->id(),
                'name' => 'Trixie',
                'magics' => array(
                    (object) array(
                        'name' => 'Nature',
                    ),
                    (object) array(
                        'name' => 'Trick',
                        'spells' => array(
                            (object) array(
                                'name' => 'Love'
                            )
                        )
                    )
                )
            ),
            (object) array(
                $idField => $blum->id(),
                'name' => 'Blum',
                'magics' => array(
                    (object) array(
                        'name' => 'Charm',
                    )
                )
            )
        ));
    }
    
    protected function addToEmptyTest()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $trixie->magics->create(array('name' => 'Nature'));
        $trixie->magics->offsetUnset(0);
        
        $trixie->save();
        
        $trixie = $this->orm->repository('fairy')->query()
            ->where('name', 'Trixie')
            ->findOne();
        
        $trixie->magics->create(array('name' => 'Nature'));
        
        $blum = $this->createEntity('fairy', array(
            'name' => 'Blum'
        ));
        
        $blum = $this->orm->repository('fairy')->query()
            ->where('name', 'Blum')
            ->findOne();
        
        $blum->magics();
        $blum->save();
        
        $blum = $this->orm->repository('fairy')->query()
            ->where('name', 'Blum')
            ->findOne();
        
        $blum->magics->create(array('name' => 'Charm'));
    }
    
    protected function removeItemTest()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $blum = $this->createEntity('fairy', array(
            'name' => 'Blum'
        ));
        
        $nature = $trixie->magics->create(array('name' => 'Nature'));
        $charm = $blum->magics->create(array('name' => 'Charm'));
        
        $love = $blum->magics[0]->spells->create(array('name' => 'Love'));
        
        $trixie->save();
        $blum->save();
        
        $trixie = $this->orm->repository('fairy')->query()
            ->where('name', 'Trixie')
            ->findOne();
        
        $blum = $this->orm->repository('fairy')->query()
                    ->where('name', 'Blum')
                    ->findOne();
        
        $this->assertSame('Nature', $trixie->magics[0]->name);
        $trixie->magics->offsetUnset(0);
        $this->assertSame(0, $trixie->magics->count());

        $this->assertSame('Love', $blum->magics[0]->spells[0]->name);
        $blum->magics[0]->spells->removeAll();
        $this->assertSame(0, $blum->magics[0]->spells->count());

        $trixie->save();
        $blum->save();
        
        $idField = $this->idField('fairy');
        $this->assertDataAsObject('fairy', array(
            (object) array($idField => $trixie->id(), 'name' => 'Trixie'),
            (object) array(
                $idField => $blum->id(),
                'name' => 'Blum',
                'magics' => array(
                    (object) array (
                        'name' => 'Charm'
                    )
                )
            )
        ));
    }
    
    protected function prepareEntities()
    {
        $map = array(
            'Trixie' => array(
                'Nature' => array('Rain', 'Wind'),
                'Animal' => array('Call'),
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
            
            foreach($magicMap as $magicName => $spells) {
                $magic = $fairy->magics->create();
                $magic->name = $magicName;
                
                foreach($spells as $spellName) {
                    $spell = $magic->spells->create();
                    $spell->name = $spellName;
                }
            }
            
            $fairy->save();
        }
        
        return $map;
    }
}