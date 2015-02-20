<?php

namespace PHPixieTests\ORM\Functional\Model;

class QueryTest extends \PHPixieTests\ORM\Functional\ModelTest
{
    protected $fairies;
    
    public function setUp()
    {
        parent::setUp();
        $this->fairies = $this->createFairies(array('Trixie', 'Blum', 'Pixie'));
    }
    
    public function testFind()
    {
        $this->assertFairyNames(
            array('Trixie', 'Blum', 'Pixie'),
            $this->query()
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Trixie'),
            $this->query()
                ->limit(1)
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Blum', 'Pixie'),
            $this->query()
                ->offset(1)
                ->limit(2)
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Blum', 'Pixie', 'Trixie'),
            $this->query()
                ->orderAscendingBy('name')
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Pixie', 'Blum'),
            $this->query()
                ->orderDescendingBy('name')
                ->offset(1)
                ->find()->asArray()
        );
    }
    
    public function testConditions()
    {
        $this->assertFairyNames(
            array('Blum', 'Pixie'),
            $this->query()
                ->where('id', '>', 1)
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Trixie'),
            $this->query()
                ->whereNot('id', '>', 1)
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Pixie'),
            $this->query()
                ->whereNot(function($b){
                    $b
                        ->and('name', 'Trixie')
                        ->or('name', 'Blum');
                })
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Pixie'),
            $this->query()
                ->whereNot(function($b){
                    $b
                        ->and('name', 'Trixie')
                        ->or('name', 'Blum');
                })
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Trixie', 'Blum', 'Pixie'),
            $this->query()
                ->whereNot(function($b){
                    $b
                        ->and('name', 'Trixie')
                        ->or('name', 'Blum');
                })
                ->orNot('id', '=', 3)
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Trixie', 'Blum'),
            $this->query()
                ->where('id', 'in', array(1, 2))
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Blum'),
            $this->query()
                ->where('id', 'in', array(1, 2))
                ->and('id', 2)
                ->find()->asArray()
        );
    }
    
    public function testInConditions()
    {
        $this->assertFairyNames(
            array('Trixie', 'Blum'),
            $this->query()
                ->where('name', 'Trixie')
                ->orIn($this->fairies[1])
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array(),
            $this->query()
                ->in(array())
                ->find()->asArray()
        );
        
        $this->assertFairyNames(
            array('Trixie', 'Blum', 'Pixie'),
            $this->query()
                ->notIn(array())
                ->find()->asArray()
        );
    }
        
    public function testCount()
    {
        $this->assertEquals(
            3,
            $this->query()
                ->count()
        );
        
        $this->assertEquals(
            2,
            $this->query()
                ->where('id', '>', 1)
                ->count()
        );
        
        $this->assertEquals(
            0,
            $this->query()
                ->where('id', '>', 3)
                ->count()
        );
    }
    
    public function testDelete()
    {
        $this->query()
                ->where('name', 'Trixie')
                ->delete();
        
        $this->assertData('fairy', array(
            array('name' => 'Blum'),
            array('name' => 'Pixie')
        ));
        
        $this->query()
                ->delete();
        
        $this->assertData('fairy', array());
    }
    
    public function testUpdate()
    {
        $this->query()
                ->where('name', 'Trixie')
                ->update(array(
                    'name' => 'Fairy'
                ));
        
        $this->assertData('fairy', array(
            array('name' => 'Fairy'),
            array('name' => 'Blum'),
            array('name' => 'Pixie')
        ));
        
        $this->query()
                ->where('name', 'Blum')
                ->getUpdateBuilder()
                    ->set('name', 'Trixie')
                ->execute();
        
        $this->assertData('fairy', array(
            array('name' => 'Fairy'),
            array('name' => 'Trixie'),
            array('name' => 'Pixie')
        ));
    }
    
    protected function query()
    {
        return $this->repository->query();
    }

}