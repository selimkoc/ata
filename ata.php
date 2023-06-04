<?php

/*
   Plugin Name: ATA 
   Plugin URI: https://github.com/selimkoc/ata
   description: ATA is a simple base plugin for creating custom MVC WordPress Plugins.
   Version: 1.0
   Author: Selim Koc
   Author URI: https://selimkoc.com
   Texts Domain: ata
   Domain Path: /languages
   License: GPL3 
   */

namespace ATA;

define('ATA\ATA_PLUGIN_DIR', __DIR__);

require_once ATA_PLUGIN_DIR . '/inc.php';

new Ata();
