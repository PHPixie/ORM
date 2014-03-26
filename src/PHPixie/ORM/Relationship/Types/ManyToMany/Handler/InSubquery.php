<?php

namespace PHPixie\ORM\Relationships\ManyToMany\Handler;

class InSubquery extends PHPixie\ORM\Relationships\ManyToMany\Handler
{
    protected $subqueryStrategy = null;

    protected function processAddQueries($queries)
    {
        $plan = null;
        $insertQuery = $this->db->query('insert')
                                            ->table($config['pivot']);
        $idUnion = $this->db->query('select')
                                        ->fields(array(
                                            $this->db->expr($sideId),
                                            $config["{$opposingSide}_model_id"]
                                        ));

        foreach ($queries as $key => $query) {
            $queryPlan = $query->plan();

            $idQuery = $plan->currentQuery();
            $idQuery->fields(array($config["{$opposingSide}_model_id"]));

            if ($key === 0) {
                $plan = $queryPlan;
                $insertQuery
                        ->batchData(
                            array(
                                $config["{$side}_pivot_key"],
                                $config["{$opposingSide}_pivot_key"]
                            ),
                            $idUnion
                        );

            } else {
                $queryPlan->removeLast();
                $plan->merge($queryPlan);
                $idUnion->union($idQuery);
            }
        }

        $plan->add($insertQuery);
        $plan->execute();
    }

    protected function processAdd($config, $side, $opposingSide, $model, $collection)
    {
        $ids = $collection->field($config["{$side}_model_id"], true);
        $this->processAddIds($config, $side, $opposingSide, $model->id(), $ids);

        $this->processAddQueries($collection->addedQueries());
    }
}
