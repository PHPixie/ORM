<?php

namespace PHPixie\ORM;

class Plans
{
    protected $transaction;

    public function steps()
    {
        return new Plans\Plan\Steps($this);
    }
    
    public function query($queryStep)
    {
        return new Plans\Plan\Query($this, $queryStep);
    }
    
    public function count($countStep)
    {
        return new Plans\Plan\Query\Count($this, $countStep);
    }

    public function loader($resultStep, $loader)
    {
        return new Plans\Plan\Query\Loader($this, $resultStep, $loader);
    }

    public function transaction()
    {
        if ($this->transaction === null)
            $this->transaction = $this->buildTransaction();

        return $this->transaction;
    }

    protected function buildTransaction()
    {
        return new Plans\Transaction();
    }
}
