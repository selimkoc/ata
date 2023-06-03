<?php

namespace ATA;

// CONFIGURATION
require_once(ATA_PLUGIN_DIR . '/config/class-config.php');

// CORE CLASSES
require_once(ATA_PLUGIN_DIR . '/app/core/class-controller.php');
require_once(ATA_PLUGIN_DIR . '/app/core/class-model.php');
require_once(ATA_PLUGIN_DIR . '/app/core/class-api.php');

// ROUTERS
require_once(ATA_PLUGIN_DIR . '/app/class-router.php');
require_once(ATA_PLUGIN_DIR . '/app/routers/class-ajax.php');
require_once(ATA_PLUGIN_DIR . '/app/routers/class-api.php');
require_once(ATA_PLUGIN_DIR . '/app/routers/class-custom-url.php');
require_once(ATA_PLUGIN_DIR . '/app/routers/class-post.php');
