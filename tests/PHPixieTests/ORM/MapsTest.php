<?php

namespace PHPixieTests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Maps
 */
class MapsTest extends \PHPixieTests\AbstractORMTest
{
    protected $relationships;
    protected $configSlice;
    
    protected $mapsMock;
    
    protected $entityMap;
    protected $queryMap;
        
    public function setUp()
    {
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        $this->configSlice = $this->getConfigSlice();
        
        $this->mapsMock = $this->mapsMock();
        
        $this->entityMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Entity');
        $this->queryMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Query');
        
        $this->method($this->mapsMock, 'buildEntityMap', $this->entityMap, array());
        $this->method($this->mapsMock, 'buildQueryMap', $this->queryMap, array());
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::entity
     * @covers ::query
     * @covers ::<protected>
     */
    public function testMaps()
    {
        $this->prepareBuildMaps();
        
        for($i=0; $i<2; $i++) {
            $this->assertSame($this->entityMap, $this->mapsMock->entity());
            $this->assertSame($this->queryMap, $this->mapsMock->query());
        }
    }
    
    /**
     * @covers ::entity
     * @covers ::<protected>
     */
    public function testEntity()
    {
        $maps = $this->maps();
        $this->prepareBuildMaps(true);
        
        $entity = $maps->entity();
        $this->assertInstance($entity, '\PHPixie\ORM\Maps\Map\Entity', array(
            'relationships' => $this->relationships
        ));
        
        $this->assertSame($entity, $maps->entity());
    }
    
    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $maps = $this->maps();
        $this->prepareBuildMaps(true);
        
        $query = $maps->query();
        $this->assertInstance($query, '\PHPixie\ORM\Maps\Map\Query', array(
            'relationships' => $this->relationships
        ));
        
        $this->assertSame($query, $maps->query());
    }
    
    protected function prepareBuildMaps($empty = false)
    {
        if(!$empty) {
            $types = array(
                'oneToMany',
                'embedsOne'
            );
        }else{
            $types = array();
        }
        
        $this->method($this->configSlice, 'keys', array_keys($types), array(), 0);
        
        foreach($types as $key => $type) {
            $slice = $this->getConfigSlice();
            $this->method($this->configSlice, 'slice', $slice, array($key), $key+1);
            $this->method($slice, 'getRequired', $type, array('type'), 0);
            
            $relationship = $this->getRelationship();
            $this->method($this->relationships, 'get', $relationship, array($type), $key);
            
            $sides = array(
                $this->getSide(),
                $this->getSide(true)
            );
            
            $this->method($relationship, 'getSides', $sides, array($slice), 0);
            
            foreach($sides as $sideKey => $side) {
                $this->method($this->entityMap, 'add', null, array($side), $key*2+$sideKey);
                
                if($sideKey === 1) {
                    $this->method($this->queryMap, 'add', null, array($side), $key);
                }
            }
        }
    }
    
    protected function getConfigSlice()
    {
        return $this->abstractMock('\PHPixie\Config\Slice');
    }
    
    protected function getRelationship()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship');
    }
    
    protected function getSide($isQuery = false)
    {
        if($isQuery) {
            return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side\Database\Query');
        }
        
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side');
    }
    
    protected function mapsMock()
    {
        return $this->getMock(
            '\PHPixie\ORM\Maps',
            array('buildEntityMap', 'buildQueryMap'),
            array($this->relationships, $this->configSlice)
        );
    }
    
    protected function maps()
    {
        return new \PHPixie\ORM\Maps($this->relationships, $this->configSlice);
    }
}