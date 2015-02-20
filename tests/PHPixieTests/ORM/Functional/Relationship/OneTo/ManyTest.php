<?php

namespace PHPixieTests\ORM\Functional\Relationship\OneTo;

class ManyTest extends \PHPixieTests\ORM\Functional\Relationship\OneToTest
{
    protected $relationshipName = 'oneToMany';
    
    public function testItemsCondtions()
    {
        $this->prepareEntities();
        
        $this->assertEntities(
            array(
                array('name' => 'Red'),
                array('name' => 'Green')
            ),
            $this->orm->get('flower')->query()
                ->relatedTo('fairy', function($b) {
                    $b->and('name', 'Trixie');
                })
                ->find()->asArray()
        );
        
        $this->assertEntities(
            array(
                array('name' => 'Yellow'),
                array('name' => 'Purple')
            ),
            $this->orm->get('flower')->query()
                ->notRelatedTo('fairy', function($b) {
                    $b->and('name', 'Trixie');
                })
                ->find()->asArray()
        );
        
        $this->assertEntities(
            array(
                array('name' => 'Red'),
                array('name' => 'Green')
            ),
            $this->orm->get('flower')->query()
                ->where('fairy.name', 'Trixie')
                ->find()->asArray()
        );
        
        $this->assertEntities(
            array(
                array('name' => 'Red'),
                array('name' => 'Green'),
                array('name' => 'Yellow'),
            ),
            $this->orm->get('flower')->query()
                ->relatedTo('fairy')
                ->find()->asArray()
        );
        
        $this->assertEntities(
            array(
                array('name' => 'Purple'),
            ),
            $this->orm->get('flower')->query()
                ->notRelatedTo('fairy')
                ->find()->asArray()
        );
        
        $trixie = $this->orm->get('fairy')->query()->findOne();
        $this->assertEntities(
            array(
                array('name' => 'Red'),
                array('name' => 'Green'),
            ),
            $this->orm->get('flower')->query()
                ->relatedTo('fairy', $trixie)
                ->find()->asArray()
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
                var_dump($flowerName);
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