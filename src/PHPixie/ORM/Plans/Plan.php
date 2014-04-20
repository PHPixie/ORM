<?php

namespace \PHPixie\ORM\Plans;

abstract class Plan
{
    abstract public function execute();
	abstract public function steps();
}
