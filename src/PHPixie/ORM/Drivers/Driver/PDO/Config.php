<?php

namespace PHPixie\ORM\Drivers\Driver\PDO;

class Config extends \PHPixie\ORM\Drivers\Driver\SQL\Config
{
    protected function driver()
    {
        return 'pdo';
    }
}