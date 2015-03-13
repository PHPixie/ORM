<?php

namespace PHPixie\Tests\ORM\Functional\Model;

class EntityTest extends \PHPixie\Tests\ORM\Functional\ModelTest
{
    public function testCreate()
    {
        $this->runTests('create');
    }
    
    public function testUpdate()
    {
        $this->runTests('update');
    }
    
    public function testDelete()
    {
        $this->runTests('delete');
    }
    
    protected function createTest()
    {
        $fairy = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $this->assertSame(false, $fairy->isNew());
        $this->assertSame(true, $fairy->id() != null);
        
        $data = array(
            'name' => 'Trixie'
        );
        
        $this->assertData('fairy', array(
            $data
        ));
    }
    
    protected function updateTest()
    {
        $fairy = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $fairy->name ='Blum';
        $fairy->save();
        
        $this->assertData('fairy', array(
            array( 'name' => 'Blum')
        ));
    }
    
    protected function deleteTest()
    {
        $fairy = $this->createEntity('fairy', array(
            'name' => 'Trixie'
        ));
        
        $this->createEntity('fairy', array(
            'name' => 'Blum'
        ));
        
        $fairy->delete();
        $this->assertSame(true, $fairy->isDeleted());
        
        $this->assertData('fairy', array(
            array( 'name' => 'Blum')
        ));
    }

}
    