<?php

namespace PHPixieTests\ORM\Mappers\Cascade\Mapper;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Cascade\Mapper\Delete
 */
class DeleteTest extends \PHPixieTests\ORM\Mappers\Cascade\MapperTest
{
    /**
     * @covers ::handleResult
     * @covers ::<protected>
     */
    public function testHandleResult()
    {
        $result = $this->getReusableResult();
        $plan = $this->getPlan();
        $relationshipTypes = array('oneToOne', 'manyToOne');
        
        $path = $this->getPath();
        
        $this->method($path, 'hasModel', false, array($this->modelName), 0);
        $sides = $this->prepareGetHandledSides($relationshipTypes);
        foreach($sides as $key => $side) {
            $sidePath = $this->getPath();
            $this->method($path, 'copy', $sidePath, array(), $key+1);
            $this->method($sidePath, 'addSide', $sidePath, array($side), 0);
            $relationship = $this->getRelationship();
            $this->method($this->relationships, 'get', $relationship, array($relationshipTypes[$key]), $key);
                
            $handler = $this->getHandler();
            $this->method($relationship, 'handler', $handler, array(), 0);
            
            $this->method($handler, 'handleDelete', null, array($side, $result, $plan, $sidePath), 0);
        }
        
        $this->cascadeMapper->handleResult($result, $this->modelName, $plan, $path);
    }
    
    protected function setSideIsHandled($side, $isHandled)
    {
        $this->method($side, 'isDeleteHandled', $isHandled, array());
    }
    
    protected function cascadeMapper()
    {
        return new \PHPixie\ORM\Mappers\Cascade\Mapper\Delete($this->relationships);
    }
    
}