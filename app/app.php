<?php

namespace ATA;

// CONFIGURATION
require_once(ATA_PLUGIN_DIR . '/config/class-config.php');

// CORE CLASSES
require_once(ATA_PLUGIN_DIR . '/app/base/class-controller.php');
require_once(ATA_PLUGIN_DIR . '/app/base/class-model.php');
require_once(ATA_PLUGIN_DIR . '/app/base/class-api.php');

// ROUTERS
require_once(ATA_PLUGIN_DIR . '/app/router/class-router.php');
require_once(ATA_PLUGIN_DIR . '/app/router/class-ajax.php');
require_once(ATA_PLUGIN_DIR . '/app/router/class-api.php');
require_once(ATA_PLUGIN_DIR . '/app/router/class-custom-url.php');
require_once(ATA_PLUGIN_DIR . '/app/router/class-post.php');
