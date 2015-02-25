<?php

namespace PHPixieTests\ORM\Functional\Relationship;

abstract class EmbedsTest extends \PHPixieTests\ORM\Functional\RelationshipTest
{
    protected $relationshipName;
    
    protected $itemKey;
    protected $itemProperty;
    protected $subItemProperty;
    
    public function setUp()
    {
        $this->ormConfigData = array(
            'models' => array(
                'magic' => array(
                    'type' => 'embedded'
                ),
                'spell' => array(
                    'type' => 'embedded'
                )
            ),
            'relationships' => array(
                array(
                    'type'  => $this->relationshipName,
                    'owner' => 'fairy',
                    $this->itemKey => 'magic'
                ),
                array(
                    'type'  => $this->relationshipName,
                    'owner' => 'magic',
                    $this->itemKey => 'spell'
                )
            )
        );
        
        parent::setUp();
    }
    /*
    public function testPreloadOwner()
    {
        $this->runTests('preloadOwner');
    }
    
    public function testSetOwner()
    {
        $this->runTests('setOwner');
    }
    
    public function testLoadOwner()
    {
        $this->runTests('loadOwner');
    }
    
    public function testRemoveOwner()
    {
        $this->runTests('removeOwner');
    }
    
    public function testItemsConditions()
    {
        $this->runTests('itemsConditions');
    }
    
    public function testOwnerCondtions()
    {
        $this->runTests('ownerCondtions');
    }
    
    public function testCascadeDeleteUpdate()
    {
        $this->runTests('cascadeDeleteUpdate');
    }
    
    public function testCascadeDelete()
    {
        $this->runTests('cascadeDelete');
    }
    */
    
    protected function prepareMongoDatabase()
    {
        $connection = $this->database->get('default');
        $collections = array('fairies');
        
        foreach($collections as $collection) {
            $connection->deleteQuery()
                        ->collection($collection)
                        ->execute();
        }
    }
    
    protected function prepareSQLDatabase($multipleConnections = false)
    {
        
    }
}