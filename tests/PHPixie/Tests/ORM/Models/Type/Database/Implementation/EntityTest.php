<?php

namespace PHPixie\Tests\ORM\Models\Type\Database\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Models\Type\Database\Implementation\Entity
 */
abstract class EntityTest extends \PHPixie\Tests\ORM\Models\Model\Implementation\EntityTest
{
    protected $repository;
    
    public function setUp()
    {
        $this->configData['idField'] = 'id';
        $this->repository = $this->getRepository();
        parent::setUp();
    }
    
    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Models\Model\Implementation\Entity::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        $this->assertSame(false, $this->entity->isNew());
        
        $entity = $this->buildEntity(true);
        $this->assertSame(true, $entity->isNew());
    }
    
    /**
     * @covers ::isNew
     * @covers ::setIsNew
     * @covers ::<protected>
     */
    public function testIsNew()
    {
        $this->assertSame(false, $this->entity->isNew());
        $this->entity->setIsNew(true);
        $this->assertSame(true, $this->entity->isNew());
    }
    
    /**
     * @covers ::isDeleted
     * @covers ::setIsDeleted
     * @covers ::<protected>
     */
    public function testIsDeleted()
    {
        $this->assertSame(false, $this->entity->isDeleted());
        $this->entity->setIsDeleted(true);
        $this->assertSame(true, $this->entity->isDeleted());
    }
                            
    /**
     * @covers ::id
     * @covers ::<protected>
     */
    public function testId()
    {
        $this->prepareGetDataField('id', null, 1);
        $this->assertEquals(1, $this->entity->id());
    }
                            
    /**
     * @covers ::setId
     * @covers ::<protected>
     */
    public function testSetId()
    {
        $this->prepareSetDataField('id', 1);
        $this->assertSame($this->entity, $this->entity->setId(1));
    }
    
    /**
     * @covers ::save
     * @covers ::<protected>
     */
    public function testSave()
    {
        $this->method($this->repository, 'save', null, array($this->entity), 0);
        $this->assertSame($this->entity, $this->entity->save());
    }
    
    /**
     * @covers ::delete
     * @covers ::<protected>
     */
    public function testDelete()
    {
        $this->method($this->repository, 'delete', null, array($this->entity), 0);
        $this->assertSame($this->entity, $this->entity->delete());
    }
    
    /**
     * @covers ::id
     * @covers ::setId
     * @covers ::getRelationshipProperty
     * @covers ::<protected>
     */
    public function testDeletedExceptions()
    {
        $this->entity->setIsDeleted(true);
        $methods = $this->deletedExceptionMethods();
        foreach($methods as $method => $params) {
            $except = false;
            try {
                call_user_func_array(array($this->entity, $method), $params);
            }catch(\PHPixie\ORM\Exception\Entity $e) {
                $except = true;
            }
            
            $this->assertSame(true, $except);
        }
    }
    
    protected function deletedExceptionMethods()
    {
        return array(
            'id' => array(),
            'setId' => array(5),
            'getRelationshipProperty' => array('test1'),
        );
    }
    
    protected function config()
    {
        $config = parent::config();
        $this->method($this->repository, 'config', $config, array());
        
        return $config;
    }
    
    abstract protected function getRepository();
    abstract protected function buildEntity($isNew = false);
}