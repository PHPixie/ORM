<?php

namespace PHPixie\Tests\ORM\Functional\Model;

class QueryTest extends \PHPixie\Tests\ORM\Functional\ModelTest
{
    public function testFind()
    {
        $this->runTests('find');
    }

    public function testConditions()
    {
        $this->runTests('conditions');
    }

    public function testInConditions()
    {
        $this->runTests('inConditions');
    }

    public function testCount()
    {
        $this->runTests('count');
    }

    public function testDelete()
    {
        $this->runTests('delete');
    }

    public function testUpdate()
    {
        $this->runTests('update');
    }

    public function testSubquery()
    {
        $this->runTests('subquery');
    }

    protected function findTest()
    {
        $this->createFairies(array('Trixie', 'Blum', 'Pixie'));

        $this->assertFairyNames(
            array('Trixie', 'Blum', 'Pixie'),
            $this->query()
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array('Trixie'),
            $this->query()
                ->limit(1)
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array('Blum', 'Pixie'),
            $this->query()
                ->offset(1)
                ->limit(2)
                ->find()->asArray()
        );

        $this->assertSame(
            array('Blum', 'Pixie'),
            array_keys($this->query()
                ->offset(1)
                ->limit(2)
                ->find()->asArray(false, 'name'))
        );

        $this->assertFairyNames(
            array('Blum', 'Pixie', 'Trixie'),
            $this->query()
                ->orderAscendingBy('name')
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array('Pixie', 'Blum'),
            $this->query()
                ->orderDescendingBy('name')
                ->offset(1)
                ->find(array(), array('name'))->asArray()
        );
    }

    protected function conditionsTest()
    {
        $fairies = $this->createFairies(array('Trixie', 'Blum', 'Pixie'));
        $idField = $this->idField('fairy');

        $this->assertFairyNames(
            array('Blum', 'Pixie'),
            $this->query()
                ->where($idField, '>', $fairies[0]->id())
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array('Trixie'),
            $this->query()
                ->whereNot($idField, '>', $fairies[0]->id())
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array('Pixie'),
            $this->query()
                ->whereNot(function($b){
                    $b
                        ->and('name', 'Trixie')
                        ->or('name', 'Blum');
                })
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array('Pixie'),
            $this->query()
                ->whereNot(function($b){
                    $b
                        ->and('name', 'Trixie')
                        ->or('name', 'Blum');
                })
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array('Trixie', 'Blum', 'Pixie'),
            $this->query()
                ->whereNot(function($b){
                    $b
                        ->and('name', 'Trixie')
                        ->or('name', 'Blum');
                })
                ->orNot($idField, '=', $fairies[2]->id())
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array('Trixie', 'Blum'),
            $this->query()
                ->where($idField, 'in', array(
                    $fairies[0]->id(),
                    $fairies[1]->id()
                ))
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array('Blum'),
            $this->query()
                ->where($idField, 'in', array(
                    $fairies[0]->id(),
                    $fairies[1]->id()
                ))
                ->and($idField, $fairies[1]->id())
                ->find()->asArray()
        );

    }

    protected function inConditionsTest()
    {
        $fairies = $this->createFairies(array('Trixie', 'Blum', 'Pixie'));

        $this->assertFairyNames(
            array('Trixie', 'Blum'),
            $this->query()
                ->where('name', 'Trixie')
                ->orIn($fairies[1])
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array(),
            $this->query()
                ->in(array())
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array('Trixie', 'Blum', 'Pixie'),
            $this->query()
                ->notIn(array())
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array('Blum'),
            $this->query()
                ->in($fairies[1]->id())
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array('Blum', 'Pixie'),
            $this->query()
                ->in(array($fairies[1]->id(), $fairies[2]->id()))
                ->find()->asArray()
        );

        $stella = $this->createEntity('fairy', array(), false);

        $query = $this->query();
        $this->assertException(function() use($query, $stella){
            $query->in($stella)->find();
        }, '\PHPixie\ORM\Exception\Builder');

    }

    protected function countTest()
    {
        $this->createFairies(array('Trixie', 'Blum', 'Pixie'));

        $this->assertEquals(
            3,
            $this->query()
                ->count()
        );

        $this->assertEquals(
            2,
            $this->query()
                ->where('name', '!=', 'Blum')
                ->count()
        );

        $this->assertEquals(
            0,
            $this->query()
                ->where('name', 'Stella')
                ->count()
        );
    }

    protected function deleteTest()
    {
        $this->createFairies(array('Trixie', 'Blum', 'Pixie'));

        $this->query()
                ->where('name', 'Trixie')
                ->delete();

        $this->assertData('fairy', array(
            array('name' => 'Blum'),
            array('name' => 'Pixie')
        ));

        $this->query()
                ->delete();

        $this->assertData('fairy', array());
    }

    protected function updateTest()
    {
        $this->createFairies(array('Trixie', 'Blum', 'Pixie'));

        $this->query()
                ->where('name', 'Trixie')
                ->update(array(
                    'name' => 'Fairy'
                ));

        $this->assertData('fairy', array(
            array('name' => 'Fairy'),
            array('name' => 'Blum'),
            array('name' => 'Pixie')
        ));

        $this->query()
                ->where('name', 'Blum')
                ->getUpdateBuilder()
                    ->set('name', 'Trixie')
                ->execute();

        $this->assertData('fairy', array(
            array('name' => 'Fairy'),
            array('name' => 'Trixie'),
            array('name' => 'Pixie')
        ));
    }

    protected function subqueryTest()
    {
        $this->createFairies(array('Trixie', 'Blum', 'Pixie', 'Stella'));

        $this->assertFairyNames(
            array('Trixie', 'Blum'),
            $this->query()
                ->in($this->query()
                        ->limit(2)
                    )
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array('Blum', 'Pixie', 'Stella'),
            $this->query()
                ->in($this->query()
                        ->offset(1)
                    )
                ->find()->asArray()
        );

        $this->assertFairyNames(
            array('Stella'),
            $this->query()
                ->in($this->query()
                        ->orderDescendingBy('name')
                        ->offset(1)
                        ->limit(1)
                    )
                ->find()->asArray()
        );
    }

    protected function query($name = 'fairy')
    {
        return parent::query($name);
    }

}
