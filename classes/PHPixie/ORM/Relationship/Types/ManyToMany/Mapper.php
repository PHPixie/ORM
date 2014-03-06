<?php

namespace PHPixie\ORM\Relationships\OneToMany;

class Mapper
{
    protected function normalizeConfig($config)
    {
        $normalized = array();

        $sides = array(
            'left' => array('opposing' => 'right'),
            'right' => array('opposing' => 'left'),
        );

        foreach ($sides as $side => $params) {
            $opposing = $params['opposing'];

            $model = $config->get($opposing);
            $normalized["{$side}_model"] = $model;

            if (($plural = $config->get("{$opposing}_property", null)) === null)
                $plural = $this->repositoryRegistry($model)->pluralName();
            $sides[$side]['plural'] = $plural;
            $normalized["{$opposing}_property"] = $plural;

            if (($pivotKey = $config->get("{$side}_pivot_key", null)) === null)
                $pivotKey = $this->inflector->singular($plural).'_id';
            $normalized["{$side}_pivot_key"] = $pivotKey;
        }

        if (($pivot = $config->get('pivot', null)) === null)
            $pivot = $sides['left']['plural'].'_'.$sides['right']['plural'];
        $normalized['pivot'] = $pivot;

        if (($pivotConnection = $config->get('pivot_connection', null)) === null)
            $pivotConnection = $this->repositoryRegistry($normalized["left_model"])->connectionName();
        $normalized['pivot_connection'] = $pivotConnection;

        return $normalized;
    }
}
