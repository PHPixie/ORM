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
    
    protected $relationshipMap;
    protected $entityPropertyMap;
    protected $queryPropertyMap;
        
    public function setUp()
    {
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        $this->configSlice = $this->getConfigSlice();
        
        $this->mapsMock = $this->mapsMock();
        
        $this->relationshipMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Relationship');
        $this->entityPropertyMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Property\Entity');
        $this->queryPropertyMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Property\Query');
        
        $this->method($this->mapsMock, 'buildRelationshipMap', $this->relationshipMap, array());
        $this->method($this->mapsMock, 'buildEntityPropertyMap', $this->entityPropertyMap, array());
        $this->method($this->mapsMock, 'buildQueryPropertyMap', $this->queryPropertyMap, array());
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
            $this->assertSame($this->relationshipMap, $this->mapsMock->relationship());
            $this->assertSame($this->entityPropertyMap, $this->mapsMock->entityProperty());
            $this->assertSame($this->queryPropertyMap, $this->mapsMock->queryProperty());
        }
    }

    
    /**
     * @covers ::relationship
     * @covers ::<protected>
     */
    public function testRelationship()
    {
        $maps = $this->maps();
        $this->prepareBuildMaps(true);
        
        $relationshipMap = $maps->relationship();
        $this->assertInstance($relationshipMap, '\PHPixie\ORM\Maps\Map\Relationship');
        
        $this->assertSame($relationshipMap, $maps->relationship());
    }
    
    /**
     * @covers ::entityProperty
     * @covers ::<protected>
     */
    public function testEntityProperty()
    {
        $maps = $this->maps();
        $this->prepareBuildMaps(true);
        
        $entityPropertyMap = $maps->entityProperty();
        $this->assertInstance($entityPropertyMap, '\PHPixie\ORM\Maps\Map\Property\Entity', array(
            'relationships' => $this->relationships
        ));
        
        $this->assertSame($entityPropertyMap, $maps->entityProperty());
    }
    
    /**
     * @covers ::queryProperty
     * @covers ::<protected>
     */
    public function testQueryProperty()
    {
        $maps = $this->maps();
        $this->prepareBuildMaps(true);
        
        $queryPropertyMap = $maps->queryProperty();
        $this->assertInstance($queryPropertyMap, '\PHPixie\ORM\Maps\Map\Property\Query', array(
            'relationships' => $this->relationships
        ));
        
        $this->assertSame($queryPropertyMap, $maps->queryProperty());
    }
    
    protected function prepareBuildMaps($empty = false)
    {
        if(!$empty) {
            $types = array(
                'oneToMany',
                'manyToMany'
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
                'relationship'   => $this->getSide('relationship'),
                'entityProperty' => $this->getSide('entity'),
                'queryProperty'  => $this->getSide('query')
            );
            
            $this->method($relationship, 'getSides', $sides, array($slice), 0);
            
            foreach($sides as $mapName => $side) {
                $mapName.='Map';
                $this->method($this->$mapName, 'add', null, array($side), $key);
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
    
    protected function getSide($type = 'relationship')
    {
        if($type === 'entity') {
            return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side\Property\Entity');
        }
        
        if($type === 'query') {
            return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side\Property\Query');
        }
        
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side\Relationship');
    }
    
    protected function mapsMock()
    {
        return $this->getMock(
            '\PHPixie\ORM\Maps',
            array(
                'buildEntityPropertyMap',
                'buildQueryPropertyMap',
                'buildRelationshipMap',
            ),
            array(
                $this->relationships,
                $this->configSlice
            )
        );
    }
    
    protected function maps()
    {
        return new \PHPixie\ORM\Maps($this->relationships, $this->configSlice);
    }
}