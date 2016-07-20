<?php

namespace PHPixie\Tests\ORM\Functional;

abstract class ModelTest extends \PHPixie\Tests\ORM\Functional\Testcase
{
    protected function createFairies($names)
    {
        $fairies = array();
        
        foreach($names as $name) {
            $fairies[] = $this->createEntity('fairy', array(
                'name' => $name
            ));
        }
        
        return $fairies;
    }
    
    protected function assertFairyNames($names, $fairies)
    {
        $data = array();
        foreach($names as $name) {
            $data[] = array('name' => $name);
        }
        
        $this->assertEntities($data, $fairies);
    }
    
    protected function runTests($name)
    {
        $this->runTestCases($name, array(
            'sqlite',
            'mysql',
            'mongo'
        ));
    }
    
    protected function prepareSqlite() {
        $this->prepareSqliteDatabase();
        
        $connection = $this->database->get('default');
        $connection->execute('
            CREATE TABLE fairies (
              id INTEGER PRIMARY KEY,
              name VARCHAR(255)
            )
        ');
        
        $this->orm = $this->orm();
    }

    protected function prepareMysql() {
        
        $this->prepareMysqlDatabase();
        
        $connection = $this->database->get('default');
        
        $connection->execute(
            'DROP TABLE IF EXISTS fairies'
        );
        $connection->execute('
            CREATE TABLE fairies (
              id INTEGER PRIMARY KEY AUTO_INCREMENT,
              name VARCHAR(255)
            )
        ');
        
        $this->orm = $this->orm();
    }

    protected function prepareMongo()
    {
        $this->prepareMongoDatabase();
        
        $connection = $this->database->get('default');
        $connection->deleteQuery()
                        ->collection('fairies')
                        ->execute();
        
        $this->orm = $this->orm();
    }
    
    protected function cleanUp()
    {
    
    }
}
