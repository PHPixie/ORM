<?php

namespace PHPixieTests\ORM\Functional\Relationship\OneTo;

class ManyTest extends \PHPixieTests\ORM\Functional\Relationship\OneToTest
{
    protected $relationshipName = 'oneToMany';
    protected $itemKey = 'items';
    protected $itemProperty = 'flowers';
    
    public function testAddItem()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $red = $this->createEntity('flower', array(
            'name' => 'Red'
        ));
        
        $green = $this->createEntity('flower', array(
            'name' => 'Green'
        ));
        
        $trixie->flowers();
        
        $trixie->flowers->add($red);
        $trixie->flowers->add($green);
        
        $this->assertSame($trixie, $red->fairy());
        $this->assertSame($trixie, $green->fairy());
        
        $this->assertSame(array($red, $green), $trixie->flowers()->asArray());

        $this->assertData('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => 1),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => 1),
        ));

        
        $blum = $this->createEntity('fairy', array(
            'name' => 'Blum'
        ));
        
        $blum->flowers();
        
        for($i=0;$i<2;$i++) {
            $blum->flowers->add($green);
            $this->assertSame(array($green), $blum->flowers()->asArray());
            
            $this->assertSame($trixie, $red->fairy());
            $this->assertSame($blum, $green->fairy());
            
            $this->assertData('flower', array(
                array( 'id' => 1, 'name' => 'Red', 'fairy_id' => 1),
                array( 'id' => 2, 'name' => 'Green', 'fairy_id' => 2),
            ));
        }
    }

    public function testRemoveItems()
    {
        $trixie = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $red = $this->createEntity('flower', array(
            'name' => 'Red'
        ));
        
        $green = $this->createEntity('flower', array(
            'name' => 'Green'
        ));
        
        $trixie->flowers();
        
        $trixie->flowers->add($red);
        $trixie->flowers->add($green);
        
        $trixie->flowers->remove($green);
        $this->assertSame(array($red), $trixie->flowers()->asArray());
        
        $this->assertSame($trixie, $red->fairy());
        $this->assertSame(null, $green->fairy());

        $this->assertData('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => 1),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => null),
        ));
        
        $trixie->flowers->removeAll();
        $this->assertSame(array(), $trixie->flowers()->asArray());
        
        $this->assertSame(null, $red->fairy());

        $this->assertData('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => null),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => null),
        ));
    }
    
    
    public function testMultipleItemsCondtions()
    {
        $this->prepareEntities();
        
        $red = $this->orm->get('flower')->query()
            ->where('name', 'Red')
            ->findOne();
        
        $green = $this->orm->get('flower')->query()
            ->where('name', 'Green')
            ->findOne();

        $this->assertNames(
            array('Trixie'),
            $this->orm->get('fairy')->query()
                ->relatedTo('flowers', $red)
                ->relatedTo('flowers', $green)
                ->find()->asArray()
        );

    }
    
    public function testLoadItems()
    {
        $this->prepareEntities();
        
        $trixie = $this->orm->get('fairy')->query()
                    ->where('name', 'Trixie')
                    ->findOne();
        
        $this->assertEntities(
            array(
                array('name' => 'Red'),
                array('name' => 'Green'),
            ),
            $trixie->flowers()->asArray()
        );
        
        $pixie = $this->orm->get('fairy')->query()
                    ->where('name', 'Pixie')
                    ->findOne();
        $this->assertEntities(
            array(),
            $pixie->flowers()->asArray()
        );
    }
    
    public function testPreloadItems()
    {
        $map = $this->prepareEntities();
        
        $fairies = $this->orm->get('fairy')->query()
                        ->find(array('flowers'))
                        ->asArray();
        
        $key = 0;
        foreach($map as $fairyName => $flowerNames) {
            if($fairyName === '') {
                continue;
            }
                
            $fairy = $fairies[$key];
            $this->assertSame($fairyName, $fairy->name);
            
            $this->assertEquals(true, $fairy->flowers->isLoaded());
            $this->assertEquals(count($flowerNames), count($fairy->flowers()->asArray()));

            foreach($fairy->flowers() as $flowerKey => $flower) {
                $this->assertSame($flowerNames[$flowerKey], $flower->name);
            }
            $key++;
        }
    }
    
    protected function prepareEntities($addWithoutOwner = true)
    {
        $map = array(
            'Trixie' => array('Red', 'Green'),
            'Blum'   => array('Yellow'),
            'Pixie'  => array()
        );
        
        if($addWithoutOwner) {
            $map[''] = array('Purple');
        }
        
        foreach($map as $fairyName => $flowerNames) {
            
            if($fairyName !== '') {
                $fairy = $this->createEntity('fairy', array(
                    'name' => $fairyName
                ));
            }
            
            $flowers = array();
            foreach($flowerNames as $flowerName) {
                $flowers[] = $this->createEntity('flower', array(
                    'name' => $flowerName
                ));
            }
            
            if($fairyName !== '') {
                $fairy->flowers->add($flowers);
            }
        }
        
        return $map;
    }
}