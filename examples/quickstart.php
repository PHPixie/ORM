<?php

require_once('vendor/autoload.php');


$slice = new \PHPixie\Slice();
$database = new \PHPixie\Database($slice->arrayData(array(
    'default' => array(
        'driver' => 'pdo',
        'connection' => 'sqlite::memory:'
    )
)));

$orm = new \PHPixie\ORM($database, $slice->arrayData(array(
    'relationships' => array(
        array(
            'type'  => 'oneToMany',
            'owner' => 'fairy',
            'items' => 'flower'
        )
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

$connection->execute('
    CREATE TABLE flowers (
      id INTEGER PRIMARY KEY,
      name VARCHAR(255),
      fairyId INTEGER
    )
');

//There are no Model classes anymore
//They have been split into Repositories, Entities and Queries
//This helps to avoid confusion, reach better separation of concerns and ponies and kittens

/*
We all hated when someone was using entities as queries:
$fairy->name = 'Trixie';
$fairy->save();
$fairy->where('name', 'Stella')->find();
*/

$fairyRepository = $orm->repository('fairy');
$flowerRepository = $orm->repository('flower');

//Here goes

///Create some fairies

$trixie = $fairyRepository->create();
$trixie->name = 'Trixie';
$trixie->save();

////Shorter version
$fairyRepository
    ->create(array('name' => 'Stella'))
        ->save();

///Create flowers

foreach(array('Red', 'Yellow', 'Green', 'Purple') as $name) {
    $flowerRepository
        ->create(array('name' => $name))
            ->save();
}


//Query
$green = $flowerRepository->query()
            ->where('id', '>', 1)
            ->startAndWhereGroup()
                ->where('name', 'Green')
                ->or('name', 'Red')
            ->endGroup()
            ->findOne();

//Relationships
//ORM v3 can handle your relationships even across different connections and databases
//You can just as well have a oneToMany between MySQL and Mongo without changing a line of code
//It also will try to use the most optimized way to process these relationships depending on database type
//E.g. if your models are within same database it will use subqueries, and will only resort to
//gathering ids and running multiple queries when a database doesn't support them (like Mongo)
$trixie->flowers->add($green);

//In ORM v2 trying to get now the fairy from $green
//would result in extra database query, and it would be a different object.
//Not anymore =)
assert($green->fairy() == $trixie);

//What if we want to associate all other flowers with $stella ?
//In ORM v2 we'd have to do it one by one
//But v3 allows queries pretty much everywhere
//We don't even need to fetch Stella from database
//This is one of the most powerful and unique parts of the ORM

$stellaQuery = $fairyRepository->query()
                    ->where('name', 'Stella');

$allExceptGreen = $flowerRepository->query()
                    ->whereNot('name', 'Green');

//All this will b achieved with a single query to the database
$stellaQuery->flowers->add($allExceptGreen);


//You can also use queries to update records
//Let's rename Purple to Blue
$flowerRepository->query()
    ->where('name', 'Purple')
    ->update(array(
        'name' => 'Blue'
    ));

//Let's try finding all fairies that have at least one flower
//And also preload their flowers
//Multiple preloading is also supported now

$fairies = $fairyRepository->query()
                    ->relatedTo('flowers')
                    ->find(array('flowers')); //preloading

//Print them with relationships as plain objects
//Useful for json_encode()
print_r($fairies->asArray(true));


//More exmaples to come =)
