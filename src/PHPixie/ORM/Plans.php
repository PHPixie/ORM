<?php

namespace PHPixie\ORM;

class Plans
{
    protected $planTransaction;

    public function plan()
    {
        return new ORM\Plans\Plan\Step($this);
    }

    public function loader()
    {
        return new ORM\Plans\Plan\Loader($this);
    }

    public function transaction()
    {
        if ($this->transaction === null)
            $this->transaction = $this->buildTransaction();

        return $this->transaction;
    }

    public function buildTransaction()
    {
        return new Plans\Plan\Transaction();
    }
}
