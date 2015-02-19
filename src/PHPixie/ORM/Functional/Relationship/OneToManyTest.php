<?php

namespace PHPixieTests\ORM\Functional;

class ModelTest extends \PHPixieTests\ORM\FunctionalTest
{
    public function testOneToMany()
    {
        $trixie = $this->createFairy('Trixie');
        
        $red = $this->createFlower('Red');
        $green = $this->createFlower('Green');
        
        $trixie->flowers->add($red);
        $trixie->flowers->add($green);
        
        $this->assertSame($trixie, $red->fairy());
        $this->assertSame($trixie, $green->fairy());
        
        $this->assertEntities('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => 1),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => 1),
        ));
        
        $blum = $this->createFairy('Blum');
        $green->fairy->set($blum);
        
        $this->assertSame($trixie, $red->fairy());
        $this->assertSame($blum, $green->fairy());
        
        $this->assertEntities('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => 1),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => 2),
        ));
        
        $fairies = $this->orm->get('fairy')->query()
                        ->find(array('flowers'))
                        ->asArray();
        
        $this->assertEntity($fairies[0]->flowers()->getByOffset(0), array(
            'id' => 1
        ));
        
        $this->assertEntity($fairies[1]->flowers()->getByOffset(0), array(
            'id' => 2
        ));

        $red->fairy->remove();
        
        $this->assertEntities('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => null),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => 2),
        ));
        
        $blum->delete();
        
        $this->assertEntities('flower', array(
            array( 'id' => 1, 'name' => 'Red', 'fairy_id' => null),
            array( 'id' => 2, 'name' => 'Green', 'fairy_id' => null),
        ));   
    }
}