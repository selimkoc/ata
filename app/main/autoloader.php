<?php

spl_autoload_register(function ($class) {

    //  Autoload only classes inside the plugin namespace
    if (strpos($class, ATA\Config::$plugin_namespace) !== false) :

        $class_types = ['Controller', 'Model', 'Service', 'Helper', 'Api'];

        foreach ($class_types as $type) :

            if (strpos($class, $type)) :

                include_once ATA\Config::$plugin_path . '/app/' . strtolower($type) . 's/' . strtolower(str_replace(ATA\Config::$plugin_namespace . '\\', '', str_replace($type, '', $class))) . '-' . strtolower($type) . '.php';

                return true;

            endif;

        endforeach;

    endif;

    return false;
});
