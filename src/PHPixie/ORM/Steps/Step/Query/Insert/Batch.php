<?php

namespace PHPixie\ORM\Steps\Step\Insert;

abstract class Batch extends \PHPixie\ORM\Steps\Step
{
    protected $fields;
    protected $data;
    protected $isExecuted = false;
    
    public function fields()
    {
        $this->assertIsExecuted();
        return $this->fields;
    }
    
    public function data()
    {
        $this->assertIsExecuted();
        return $this->data;
    }
    
    protected function assertIsExecuted()
    {
        if(!$this->isExecuted)
            throw new \PHPixie\ORM\Exception\Plan("This step has not been executed yet");
    }
    
    public function execute()
    {
        parent::execute();
        $this->isExecuted = true;
    }
}