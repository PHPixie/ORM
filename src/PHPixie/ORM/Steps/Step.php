<?php

namespace PHPixie\ORM\Steps;

abstract class Step
{
    public function execute();
	public function usedConnections() {
		return array();
	}
}
