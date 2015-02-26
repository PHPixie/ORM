<?php

namespace PHPixieTests\ORM\Functional\Relationship;

abstract class EmbedsTest extends \PHPixieTests\ORM\Functional\RelationshipTest
{
    protected $testCases = array('mongo');
    
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
    
    public function testItemsConditions()
    {
        $this->runTests('itemsConditions');
    }
    
    protected function itemsConditionsTest()
    {
        $this->prepareEntities();
        
        $subProperty = $this->itemProperty.'.'.$this->subItemProperty;
        
        $this->assertNames(
            array('Trixie'),
            $this->orm->get('fairy')->query()
                ->relatedTo($this->itemProperty, function($b) {
                    $b->and('name', 'Nature');
                })
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Blum', 'Pixie', 'Stella'),
            $this->orm->get('fairy')->query()
                ->notRelatedTo($subProperty, function($b) {
                    $b->and('name', 'Rain');
                })
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Blum'),
            $this->orm->get('fairy')->query()
                ->where($this->itemProperty.'.name', 'Charm')
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Trixie', 'Blum', 'Pixie'),
            $this->orm->get('fairy')->query()
                ->relatedTo($this->itemProperty)
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Trixie', 'Blum'),
            $this->orm->get('fairy')->query()
                ->relatedTo($subProperty)
                ->find()->asArray()
        );
                
        $this->assertNames(
            array('Stella'),
            $this->orm->get('fairy')->query()
                ->notRelatedTo($this->itemProperty)
                ->find()->asArray()
        );
        
        $this->assertNames(
            array('Pixie', 'Stella'),
            $this->orm->get('fairy')->query()
                ->notRelatedTo($subProperty)
                ->find()->asArray()
        );

    }
    
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
    
    protected function prepareSqlDatabase($multipleConnections = false)
    {
        
    }
}