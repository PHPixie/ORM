<?php
spl_autoload_register(function ($class) {
    $folders = array(__DIR__, dirname(__DIR__).'/src');
    $name = str_replace("\\", "/", $class).'.php';
    foreach ($folders as $folder) {
        $file = $folder.'/'.$name;
        if (file_exists($file))
            require_once($file);
    }
});
