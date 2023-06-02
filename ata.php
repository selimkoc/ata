<?php

/*
   Plugin Name: Ata 
   Plugin URI: https://github.com/selimkoc/ata
   description: Ata is a simple MVC architecture base plugin for WordPress.
   Version: 1.0
   Author: Selim Koc
   Author URI: https://www.selimkoc.com
   License: GPL2
   */

namespace Ata;

define('Ata\ATA_PLUGIN_DIR', __DIR__);

require_once(ATA_PLUGIN_DIR . '/app/init.php');
