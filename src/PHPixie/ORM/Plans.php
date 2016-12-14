<?php

namespace PHPixie\ORM;

class Plans
{
    /**
     * @return Plans\Plan\Steps
     */
    public function steps()
    {
        return new Plans\Plan\Steps($this);
    }

    /**
     * @param $queryStep
     * @return Plans\Plan\Query
     */
    public function query($queryStep)
    {
        return new Plans\Plan\Query($this, $queryStep);
    }

    /**
     * @param $countStep
     * @return Plans\Plan\Query\Count
     */
    public function count($countStep)
    {
        return new Plans\Plan\Query\Count($this, $countStep);
    }

    /**
     * @param $resultStep
     * @param $loader
     * @return Plans\Plan\Query\Loader
     */
    public function loader($resultStep, $loader)
    {
        return new Plans\Plan\Query\Loader($this, $resultStep, $loader);
    }

    /**
     * @return Plans\Transaction
     */
    public function transaction($connections)
    {
        return new Plans\Transaction($connections);
    }
}
