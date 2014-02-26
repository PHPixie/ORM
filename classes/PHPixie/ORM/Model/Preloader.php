<?php

namespace PHPixie\ORM\Model;

interface Preloader{
	public function load_for($model);
}