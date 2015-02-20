<?php

namespace PHPixieTests\ORM\Functional\Model;

class EntityTest extends \PHPixieTests\ORM\Functional\ModelTest
{
    public function testCreate()
    {
        $fairy = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $data = array(
            'id' => 1,
            'name' => 'Trixie'
        );
        
        $this->assertData('fairy', array(
            $data
        ));
    }
    
    public function testUpdate()
    {
        $fairy = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $fairy->name ='Blum';
        $fairy->save();
        
        $this->assertData('fairy', array(
            array( 'id' => 1, 'name' => 'Blum')
        ));
    }
    
    public function testDelete()
    {
        $fairy = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $this->createEntity('fairy', array(
            'name' => 'Blum'
        ));
        
        $fairy->delete();
        
        $this->assertData('fairy', array(
            array( 'id' => 2, 'name' => 'Blum')
        ));
    }

}
    