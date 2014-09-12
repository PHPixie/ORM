<?php

namespace PHPixie\ORM;

class Plans
{
    protected $transaction;

    public function plan()
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
