<?php

namespace PHPixie\ORM\Driver\Mongo\Repository;

class Database extends \PHPixie\ORM\Model\Repository\Database
{
    public function databaseQuery($type)
    {
        return $this->connection()
                    ->query($type)
                    ->collection($this->collection);
    }

    public function save($model)
    {
        $data = $model->data();
        $diff = $data->getDataDiff();
        
        if ($model->isNew()) {
            $this->databaseQuery('insert')
                        ->data($diff->set())
                        ->execute();
            $model->setId($this->connection()->insertId());
            $model->setIsNew(false);
        }else {
            $this->dbQuery('update')
                ->data($diff->set())
                ->unset($diff->unset())
                ->where($idField, $model->id())
                ->execute();
        }
        
        $data->setCurrentAsOriginal();
    }
}
