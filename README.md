# ORM

PHPixie ORM library

[![Build Status](https://travis-ci.org/PHPixie/ORM.svg?branch=master)](https://travis-ci.org/PHPixie/ORM)
[![Test Coverage](https://codeclimate.com/github/PHPixie/ORM/badges/coverage.svg)](https://codeclimate.com/github/PHPixie/ORM)
[![Code Climate](https://codeclimate.com/github/PHPixie/ORM/badges/gpa.svg)](https://codeclimate.com/github/PHPixie/ORM)
[![HHVM Status](https://img.shields.io/hhvm/phpixie/orm.svg?style=flat-square)](http://hhvm.h4cc.de/package/phpixie/orm)

[![Author](http://img.shields.io/badge/author-@dracony-blue.svg?style=flat-square)](https://twitter.com/dracony)
[![Source Code](http://img.shields.io/badge/source-phpixie/orm-blue.svg?style=flat-square)](https://github.com/phpixie/orm)
[![Software License](https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square)](https://github.com/phpixie/orm/blob/master/LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/phpixie/orm.svg?style=flat-square)](https://packagist.org/packages/phpixie/orm)


# Initializing

```php

$slice = new \PHPixie\Slice();
$database = new \PHPixie\Database($slice->arrayData(array(
    'default' => array(
        'driver' => 'pdo',
        'connection' => 'sqlite::memory:'
    )
)));
$orm = new \PHPixie\ORM($database, $slice->arrayData(array(
    // We will add some configuration later
)));
```

> If you are using the PHPixie Framework the ORM component is already set up for you.
> It is accessable via `$frameworkBuilder->components()->orm()` and can be confgigured
> in the `config/orm.php` file of your bundle.

# Models

A Model consists of a Repository, Queries and Entities and will usuaully map to a table in a relational database
or a document collection in MongoDB.

* Entity - a single item, stored in the database, e.g. an article
* Repository - is used to create and save Entities
* Query - is used to select, update or delete Entities

You can start using the models right away without any configuration, which will use defaults based on the name of the model.

```php
$repository = $orm->repository('article');

$newArticle   = $repository->createEntity();
$articleQuery = $repository->query();

// shorthands
$newArtcile   = $orm->createEntity('article');
$articleQuery = $orm->query('article');
```

## Configuration 

By default ORM assumes that thte table name is the plural of the name of the model, and that the name of the primary key is 'id'.
For MongoDB database the default id field '_id' is assumed. You can ovveride these settings for a particular model in your
configuration file:

```php
return array(
    'models' => array(
        'article' => array(
            'type'       => 'database',
            'connection' => 'default',
            'idField'    => 'id'
        ),
        
        // you can also define embedded models
        // if you are using MongoDB,
        // more on that later
    )
);
```

## Entities

```php
// Saving an entity
$article->title = 'Welcome';
$article->save();

// Getting a field with a default value
$article->getField('title', 'No Title');

// Getting a required field
// Will throw an Exception if it is not set
$article->getRequiredField('title');1

// Convert to a simple PHP object
// Usefull for serializing
$object = $article->asObject();

// Deleting
$article->delete();
```

## Queries

ORM queries share a lot of syntax with the Database queries, here are a few query examples:

```php
// Find article by name
$article = $orm->query('article')
    ->where('title', 'Welcome')
    ->findOne();

// Find by id
$article = $orm->query('article')
    ->in($id)
    ->findOne();

// Query by multiple ids
$articles = $orm->query('article')
    ->in($ids)
    ->findOne();

// Actually the in() method can be used
// to also include subqueries and entities.
//
// This will select artciles with id '1',
// or the id same as $someArticle, and
// anything matched by $subQuery
$articles = $orm->query('article')
    ->in(array(
        1, $someArticle, $subQuery
    ));

// Multiple conditions
$articles = $orm->query('article')
    ->where('viewsTotal', '>', 2)
    ->or('viewsDone', '<', 5)
    ->find();

// It continues in the same way
// as in the Database component

$articles = $orm->query('article')
    ->where('name', 'Welcome')
    ->or(function($query) {
        $querty
            ->where('viewsTotal', '>', 2)
            ->or('viewsDone', '<', 5);
    })
    ->find();

// Alternative syntax for
// nested conditions
$articles = $orm->query('article')
    ->where('name', 'Welcome')
    ->startWhereConditionGroup('or')
        ->where('viewsTotal', '>', 2)
        ->or('viewsDone', '<', 5)
    ->endGroup()
    ->find();
```

If `findOne` is used, a query will return either a single item or `null`.
When using `find` a Loader is returned which can be used as follows:

```php
// Iterate over it
// Note: this can be done only once
foreach($articles as $article) {
    // ...
}

// Convert into an array
// to allow multiple iterations
$articles = $articles->asArray();

// Convert it into plain objects,
// useful for serializing
$data = $articles->asArray(true);
```

## Relationships

PHPixie supports one-to-many, one-to-one, many-to-many relationships,
as well as embeds-one and embeds-many for embedded models. Each relationship
defines a set of properties that are added to entities and queries
and can be used to access related data. You can configure them using the 
`relationships` key in the configuration file:

```php
return array(
    'models' => array(
        // ...
    ),
    'relationships' => array(
        array(
            'type'  => 'manyToMany',
            'left'  => 'article',
            'right' => 'tag'
        )
    )
);
```

### One To Many

This is the most common relationship. A category can own many articles, a topic has manty replies, etc.

```php
// Configuration 
return array(
    'models' => array(
        // ...
    ),
    'relationships' => array(
        //...
        
        array(
            // mandatory options
            'type'  => 'oneToMany',
            'owner' => 'category',
            'items' => 'article',
            
            // The following keys are optional
            // and will fallback to the defaults
            // based on model names
            
            // the field that is used to link items
            'ownerKey' => 'categoryId',
            
            // The default behavior is to set
            // the field defined in ownerKey to null
            // when the owner gets deleted
            //
            // changed it to 'delete' to remove
            // all category articles when a category
            // is removed
            'onDelete' => 'update'
            
            // the name of the property added to the owner
            // e.g. $category->articles();
            'ownerItemsProperty' => 'articles',
            
            // the name of the property added to items1
            // e.g. $article->category()
            'itemOwnerProperty' => 'category'
        )
    )
);
```

