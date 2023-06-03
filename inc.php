<?php

namespace ATA;

// CONFIGURATION
require_once(ATA_PLUGIN_DIR . '/config/config.php');
require_once(ATA_PLUGIN_DIR . '/config/text.php');

// HELPERS
require_once(ATA_PLUGIN_DIR . '/app/helpers/ata.php');

// BASE CLASSES
require_once(ATA_PLUGIN_DIR . '/app/base/core.php');
require_once(ATA_PLUGIN_DIR . '/app/base/controller.php');
require_once(ATA_PLUGIN_DIR . '/app/base/model.php');
require_once(ATA_PLUGIN_DIR . '/app/base/api.php');

// ROUTERS
require_once(ATA_PLUGIN_DIR . '/app/router/router.php');
require_once(ATA_PLUGIN_DIR . '/app/router/ajax.php');
require_once(ATA_PLUGIN_DIR . '/app/router/api.php');
require_once(ATA_PLUGIN_DIR . '/app/router/url.php');
require_once(ATA_PLUGIN_DIR . '/app/router/post.php');

// APP
// ATA Class
require_once(ATA_PLUGIN_DIR . '/app/main/ata.php');
