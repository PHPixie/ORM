<?php

namespace PHPixie\ORM\Planners;

class Collection
{
    protected $modelName;
    
    protected $entities;
    protected $queries;

    public function __construct($modelName, $items = array())
    {
        $this->modelName = $modelName;
        foreach($items as $item) {
            $his->add($item);
        }
    }

    protected function add($item)
    {
        $itemModel = $item->modelName();
        if ($item->modelName() !== $this->modelName) {
            throw new \PHPixie\ORM\Exception\Planner("Item of '$itemModel' cannot be used in a {$this->modelName} collection");
        }
        
        if ($item instanceof \PHPixie\ORM\Models\Type\Database\Entity) {
            if ($item->modelName() !== $this->requiredModel)
                throw new \PHPixie\ORM\Exception\Planner("Instance of the '{$item->modelName()}' model passed, but '{$this->requiredModel}' was expected.");

            if ($item->isNew())
                throw new \PHPixie\ORM\Exception\Mapper("You can only use saved models.");

            if ($item->isDeleted())
                throw new \PHPixie\ORM\Exception\Mapper("You cannot use deleted models for relationships.");

            $this->models[] = $item;
        } elseif ($item instanceof \PHPixie\ORM\Query) {

            if ($item->modelName() !== $this->requiredModel)
                throw new \PHPixie\ORM\Exception\Mapper("Query for the '{$item->modelName()}' model passed, but '{$this->requiredModel}' was expected.");

            $this->queries[] = $item;
        }else{
            print_r($item);die;
            throw new \PHPixie\ORM\Exception\Mapper("Only '{$this->requiredModel}' models and queries are allowed.");
        }
    }

    public function models()
    {
        return $this->models;
    }

    public function queries()
    {
        return $this->queries;
    }

    public function modelFields($fields)
    {
        $data = array();
        foreach ($this->models as $model) {
            $dataRow = array();
            foreach($fields as $field)
                $dataRow[$field] = $model->$field;
            $data[] = $dataRow;
        }

        return $data;
    }

    public function modelField($field)
    {
        return array_column($this->modelFields(array($field), $field));
    }
    
    public function connection()
    {}

}
