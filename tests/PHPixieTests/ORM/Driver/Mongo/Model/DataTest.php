<?php

namespace PHPixieTests\ORM\Driver\Mongo\Model;

class DataTest extends \PHPUnit_Framework_TestCase
{

    protected $types;
    protected $data;
    
    protected function setUp()
    {
        $this->types = new \PHPixie\ORM\Driver\Mongo\Model\Data\Types;
    }
    
    public function testModified()
    {
        $old = new \stdClass;
        $old->a = 5;
        $old->b = 'pixie';
        $old->c = new \stdClass;
        $old->d = new \stdClass;
        $old->d->da = new \stdClass;
        $old->e = new \stdClass;
        $old->c->ca = 'trixie';
        $old->c->cb = array(5, 6, new \stdClass);
        $old->c->cb[2]->cba = 5;
        $old->d->da->daa = 4;
        $old->d->da->dab = 'test';
        $old->d->da->dac = 6;
        $old->d->db = 3;
        $old->e->ea = array(1, 2);

        $data = new \PHPixie\ORM\Driver\Mongo\Model\Data($this->types->subdocument($old), $old);
        $data->setModel($data);
        
        unset($data->a);
        $data->b = 'trixie';
        $data->c->ca = 'pixie';
        $old->c->cb[2]->cbb = 6;

        unset($data->d->da->daa);
        $data->d->da->dab = 4;
        
        $data->d->dc = new \stdClass;
        $data->d->dc->dca = 5;
        
            
        $data->e->ea->push(8);
        $data->f = 9;
        
        print_r($data->modified());
    }
}