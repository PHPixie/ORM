<?php

namespace PHPixie\Tests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Maps
 */
class MapsTest extends \PHPixie\Test\Testcase
{
    protected $ormBuilder;
    protected $configSlice;
    
    protected $mapsMock;
    
    protected $relationships;
    
    protected $relationshipMap;
    protected $entityPropertyMap;
    protected $queryPropertyMap;
    protected $preloadMap;
    protected $cascadeDeleteMap;
        
    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');
        $this->configSlice = $this->getConfigSlice();
        
        $this->mapsMock = $this->mapsMock();
        
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        $this->method($this->ormBuilder, 'relationships', $this->relationships, array());
        
        $this->relationshipMap   = $this->quickMock('\PHPixie\ORM\Maps\Map\Relationship');
        $this->entityPropertyMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Property\Entity');
        $this->queryPropertyMap  = $this->quickMock('\PHPixie\ORM\Maps\Map\Property\Query');
        $this->preloadMap        = $this->quickMock('\PHPixie\ORM\Maps\Map\Preload');
        $this->cascadeDeleteMap  = $this->quickMock('\PHPixie\ORM\Maps\Map\Cascade\Delete');
        
        $this->method($this->mapsMock, 'buildRelationshipMap', $this->relationshipMap, array());
        $this->method($this->mapsMock, 'buildEntityPropertyMap', $this->entityPropertyMap, array());
        $this->method($this->mapsMock, 'buildQueryPropertyMap', $this->queryPropertyMap, array());
        $this->method($this->mapsMock, 'buildPreloadMap', $this->preloadMap, array());
        $this->method($this->mapsMock, 'buildCascadeDeleteMap', $this->cascadeDeleteMap, array());
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::entityProperty
     * @covers ::queryProperty
     * @covers ::relationship
     * @covers ::<protected>
     */
    public function testMaps()
    {
        $this->prepareBuildMaps();
        
        for($i=0; $i<2; $i++) {
            $this->assertSame($this->relationshipMap, $this->mapsMock->relationship());
            $this->assertSame($this->entityPropertyMap, $this->mapsMock->entityProperty());
            $this->assertSame($this->queryPropertyMap, $this->mapsMock->queryProperty());
            $this->assertSame($this->preloadMap, $this->mapsMock->preload());
            $this->assertSame($this->cascadeDeleteMap, $this->mapsMock->cascadeDelete());
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
    }
    
    /**
     * @covers ::preload
     * @covers ::<protected>
     */
    public function testPreload()
    {
        $maps = $this->maps();
        $this->prepareBuildMaps(true);
        
        $preloadMap = $maps->preload();
        $this->assertInstance($preloadMap, '\PHPixie\ORM\Maps\Map\Preload');
    }
    
    /**
     * @covers ::cascadeDelete
     * @covers ::<protected>
     */
    public function testCascadeDelete()
    {
        $maps = $this->maps();
        $this->prepareBuildMaps(true);
        
        $cascadeDeleteMap = $maps->cascadeDelete();
        $this->assertInstance($cascadeDeleteMap, '\PHPixie\ORM\Maps\Map\Cascade\Delete');
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
            
            $skip = array(
                $this->getCascadeDeleteSide(false)
            );
            
            $sides = array(
                'relationship'   => $this->getSide('relationship'),
                'entityProperty' => $this->getSide('entity'),
                'queryProperty'  => $this->getSide('query'),
                'preload'        => $this->getSide('preload'),
                'cascadeDelete'  => $this->getCascadeDeleteSide(),
            );
            
            $this->method($relationship, 'getSides', array_merge($skip, $sides), array($slice), 0);
            
            foreach($sides as $mapName => $side) {
                $mapName.='Map';
                $this->method($this->$mapName, 'add', null, array($side), $key);
            }
        }
    }
    
    protected function getConfigSlice()
    {
        return $this->abstractMock('\PHPixie\Slice\Data');
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
        
        if($type === 'preload') {
            return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side\Preload');
        }
        
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side\Relationship');
    }
    
    protected function getCascadeDeleteSide($isDeleteHandled = true)
    {
        $side = $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side\Cascade\Delete');
        $this->method($side, 'isDeleteHandled', $isDeleteHandled, array(), 0);
        
        return $side;
    }
    
    protected function mapsMock()
    {
        return $this->getMock(
            '\PHPixie\ORM\Maps',
            array(
                'buildEntityPropertyMap',
                'buildQueryPropertyMap',
                'buildRelationshipMap',
                'buildPreloadMap',
                'buildCascadeDeleteMap',
            ),
            array(
                $this->ormBuilder,
                $this->configSlice
            )
        );
    }
    
    protected function maps()
    {
        return new \PHPixie\ORM\Maps($this->ormBuilder, $this->configSlice);
    }
}