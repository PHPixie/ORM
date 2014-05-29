<?php
$root = dirname(__DIR__);
$loader = require_once($root.'/vendor/autoload.php');
$loader->add('PHPixie', $root.'/src/');
$loader->add('PHPixieTests', $root.'/tests/');