<?php

namespace PHPixie\Tests\ORM\Mappers\Cascade\Mapper;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Cascade\Mapper\Delete
 */
class DeleteTest extends \PHPixie\Tests\ORM\Mappers\Cascade\MapperTest
{
    protected $models;
    protected $planners;
    protected $steps;
    
    protected $databaseModel;
    protected $inPlanner;
    
    public function setUp()
    {
        $this->models = $this->quickMock('\PHPixie\ORM\Models');
        $this->planners = $this->quickMock('\PHPixie\ORM\Planners');
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
        
        $this->databaseModel = $this->quickMock('\PHPixie\ORM\Models\Type\Database');
        $this->method($this->models, 'database', $this->databaseModel, array());
        
        $this->inPlanner = $this->quickMock('\PHPixie\ORM\Planners\Planner\In');
        $this->method($this->planners, 'in', $this->inPlanner, array());

        parent::setUp();
    }
    
    /**
     * @covers \PHPixie\ORM\Mappers\Cascade\Mapper::__construct
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    
    /**
     * @covers ::handleResult
     * @covers ::<protected>
     */
    public function testHandleResult()
    {
        $result = $this->getReusableResult();
        $plan = $this->getPlan();
        $path = $this->getPath();
        
        $this->method($path, 'containsModel', true, array($this->modelName), 0);
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Mapper');
        $this->cascadeMapper->handleResult($result, $this->modelName, $plan, $path);
    }
    
    /**
     * @covers ::handleResult
     * @covers ::<protected>
     */
    public function testHandleResultClosedPath()
    {
        $result = $this->getReusableResult();
        $plan = $this->getPlan();
        $path = $this->getPath();
        
        $this->prepareHandleResult($result, $plan, $path);
        
        $this->cascadeMapper->handleResult($result, $this->modelName, $plan, $path);
    }
    
    /**
     * @covers ::handleQuery
     * @covers ::<protected>
     */
    public function testHandleQuery()
    {
        $selectQuery = $this->getDatabaseQuery('select');
        $plan = $this->getPlan();
        $path = $this->getPath();
        
        $this->prepareHandleQuery($selectQuery, $plan, $path);
        
        $this->cascadeMapper->handleQuery($selectQuery, $this->modelName, $plan, $path);
    }
    
    /**
     * @covers ::map
     * @covers ::<protected>
     */
    public function testMap()
    {
        $selectQuery = $this->getDatabaseQuery('select');
        $deleteQuery = $this->getDatabaseQuery('delete');
        
        $plan = $this->getPlan();
        
        $path = $this->getPath();
        $this->method($this->mappers, 'cascadePath', $path, array(), 0);
        
        $this->prepareMapDeleteQuery($deleteQuery, $selectQuery, $plan, $path);
        
        $this->cascadeMapper->map($deleteQuery, $selectQuery, $this->modelName, $plan);
    }
    
    protected function prepareHandleQuery($selectQuery, $plan, $path)
    {
        $repository = $this->prepareRepository();
        
        $deleteQuery = $this->getDatabaseQuery('delete');
        $this->method($repository, 'databaseDeleteQuery', $deleteQuery, array(), 0);
        
        $this->prepareMapDeleteQuery($deleteQuery, $selectQuery, $plan, $path, $repository, 1);
        
        $deleteStep = $this->getQueryStep();
        $this->method($this->steps, 'query', $deleteStep, array($deleteQuery), 1);
        $this->method($plan, 'add', null, array($deleteStep), 1);
        
    }
    
    protected function prepareMapDeleteQuery($deleteQuery, $selectQuery, $plan, $path, $repository = null, $repositoryOffset = 0)
    {
        if($repository === null) {
            $repository = $this->prepareRepository();
        }
        $resultStep = $this->getReusableResultStep();
        $this->method($this->steps, 'reusableResult', $resultStep, array($selectQuery), 0);
        $this->method($plan, 'add', null, array($resultStep), 0);
        
        $this->prepareHandleResult($resultStep, $plan, $path);
        
        $config = $this->config(array('idField' => 'id'));
        $this->method($repository, 'config', $config, array(), $repositoryOffset);
        
        $this->method($this->inPlanner, 'result', null, array($deleteQuery, 'id', $resultStep, 'id', $plan), 0);
       
    }
    
    protected function prepareHandleResult($result, $plan, $path)
    {
        $relationshipTypes = array('oneToOne', 'manyToOne');
                
        $this->method($path, 'containsModel', false, array($this->modelName), 0);
        
        $sides = array(
            $this->getSide(),
            $this->getSide()
        );
        
        $this->method($this->cascadeMap, 'getModelSides', $sides, array($this->modelName), 0);
        
        foreach($sides as $key => $side) {
            $this->method($side, 'relationshipType', $relationshipTypes[$key], array());
            $sidePath = $this->getPath();
            $this->method($path, 'copy', $sidePath, array(), $key+1);
            $this->method($sidePath, 'addSide', $sidePath, array($side), 0);
            $relationship = $this->getRelationship();
            $this->method($this->relationships, 'get', $relationship, array($relationshipTypes[$key]), $key);
                
            $handler = $this->getHandler();
            $this->method($relationship, 'handler', $handler, array(), 0);
            
            $this->method($handler, 'handleDelete', null, array($side, $result, $plan, $sidePath), 0);
        }
    }
    
    protected function prepareRepository()
    {
        $repository = $this->getRepository();
        $this->method($this->databaseModel, 'repository', $repository, array($this->modelName));
        return $repository;
    }
    
    protected function getSide()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side\Cascade\Delete');
    }
    
    protected function getHandler()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Handler\Cascading\Delete');
    }
    
    protected function getDatabaseQuery($type)
    {
        return $this->abstractMock('\PHPixie\Database\Query\Type\\'.ucfirst($type));
    }
    
    protected function getQueryStep()
    {
        return $this->quickMock('\PHPixie\ORM\Steps\Step\Query');
    }
    
    protected function getReusableResultStep()
    {
        return $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
    }
    
    protected function getRepository()
    {
        return $this->quickMock('\PHPixie\ORM\Models\Type\Database\Repository');
    }
    
    protected function config($properties)
    {
        $config = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Config');
        foreach($properties as $key => $value) {
            $config->$key = $value;
        }
        return $config;
    }

    protected function cascadeMap()
    {
        return $this->quickMock('\PHPixie\ORM\Maps\Map\Cascade\Delete');
    }
    
    protected function cascadeMapper()
    {
        return new \PHPixie\ORM\Mappers\Cascade\Mapper\Delete(
            $this->mappers,
            $this->relationships,
            $this->models,
            $this->planners,
            $this->steps,
            $this->cascadeMap
        );
    }
    
}