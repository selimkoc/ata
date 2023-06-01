<?php

/*
   Plugin Name: Tay 
   Plugin URI: https://github.com/selimkoc/tay_wp
   description: A simple MVC architecture base plugin for WordPress.
   Version: 1.0
   Author: Selim Koc
   Author URI: https://www.selimkoc.com
   License: GPL2
   */

namespace Tay;

// CONFIGURATION
require_once(__DIR__ . '/config/class-config.php');

// CORE CLASSES
require_once(__DIR__ . '/core/class-controller.php');
require_once(__DIR__ . '/core/class-model.php');
require_once(__DIR__ . '/core/class-api.php');

// ROUTER
require_once(__DIR__ . '/main/class-router.php');
