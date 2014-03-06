<?php

namespace \PHPixie\ORM\Driver\PDO\Relationship\BalongsTo;

class Handler extends \PHPixie\ORM\Relationship\Handler
{
    abstract protected function normalizeConfig($modelName, $relationshipName)
    {
        $config = $registry->get($modelName)->relationshipConfig($relationshipName);
        $targetId = $registry->get($config['model'])->idField();

        return array(
            'model' => $config->get('model', $relationshipName),
            'key' => $config->get('model_name', $relationshipName.'_id'),
            'id_field' => $targetId
        );
    }

    public function set($relationshipName, $model, $owner)
    {
        $config = $this->config($model, $relationshipName);

        if (!$owner->loaded())
            throw new \PHPixie\ORM\Exception\Relationship("You should save the model before assigning it to a belongs_to relationship.");

        if (!$owner->modelName() !== $config['model'])
            throw new \PHPixie\ORM\Exception\Relationship("You can only assign '{$config['model_name']}' models to this relationship.");

        $model->setProperty($config['key'], $owner->id());

        if ($model->loaded())
            $model->save();
    }

    public function get($propertyName, $model)
    {
        return $this->orm->query($config['model'])
                            ->where($config['id_field'], $model->getProperty($config['key']))
                            ->find();
    }
}
