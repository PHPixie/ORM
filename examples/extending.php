<?php

require_once('vendor/autoload.php');

$slice = new \PHPixie\Slice();
$database = new \PHPixie\Database($slice->arrayData(array(
    'default' => array(
        'driver' => 'pdo',
        'connection' => 'sqlite::memory:'
    )
)));

//Create tables
$connection = $database->get('default');

$connection->execute('
    CREATE TABLE fairies (
      id INTEGER PRIMARY KEY,
      name VARCHAR(255)
    )
');


//Create wrapper classes

class FairyEntity extends \PHPixie\ORM\Wrappers\Type\Database\Entity
{
    public function greet()
    {
        return "Hello, my name is {$this->name}";
    }
}


//Create wrapper builder
class Wrappers extends \PHPixie\ORM\Wrappers\Implementation
{
    //Tell which models have wrapped entities
    public function databaseEntities()
    {
        return array('fairy');
    }
    
    public function fairyEntity($entity)
    {
        return new FairyEntity($entity);
    }
}

//Set up ORM
$orm = new \PHPixie\ORM($database, $slice->arrayData(array()), new Wrappers);

$trixie = $orm->repository('fairy')->create();
$trixie->name = 'Trixie';
echo $trixie->greet()."\n";