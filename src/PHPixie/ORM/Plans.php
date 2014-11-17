<?php

namespace PHPixie\ORM;

class Plans
{
    protected $transaction;

    public function steps()
    {
        return new Plans\Plan\Steps($this);
    }
    
    public function query()
    {
        return new Plans\Plan\Step($this);
    }

    public function loader()
    {
        return new Plans\Plan\Composite\Loader($this);
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
