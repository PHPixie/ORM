<?php

namespace PHPixieTests\ORM\Functional;

class ModelTest extends \PHPixieTests\ORM\FunctionalTest
{
    public function testFind()
    {
        $this->createFairy('Trixie');
        
        $data = array(
            'id' => 1,
            'name' => 'Trixie'
        );
        
        $this->assertEntities('fairy', array(
            $data
        ));
        
        $fairy = $this->fairiesRepository->query()
                        ->findOne();
        
        $this->assertEntity($fairy, $data);
    }
    
    public function testUpdate()
    {
        $fairy = $this->createFairy('Trixie');
        
        $fairy->name ='Blum';
        $fairy->save();
        
        $this->assertEntities('fairy', array(
            array( 'id' => 1, 'name' => 'Blum')
        ));
    }
    
    public function testDelete()
    {
        $fairy = $this->createFairy('Trixie');
        $this->createFairy('Blum');
        
        $fairy->delete();
        
        $this->assertEntities('fairy', array(
            array( 'id' => 2, 'name' => 'Blum')
        ));
    }
}