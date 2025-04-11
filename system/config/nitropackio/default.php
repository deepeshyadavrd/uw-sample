<?php

// Never modify this file. Instead, create a new file called override.php in the same directory as this file. If a constant in override.php exists, it takes precedence over the constant in this file.

// Main constants. Do not modify unless you know what you are doing.
if (!defined('NITROPACK_DIR_BASE')) define('NITROPACK_DIR_BASE', DIR_SYSTEM . 'library/nitropackio/');
if (!defined('NITROPACK_DIR_SETTING')) define('NITROPACK_DIR_SETTING', NITROPACK_DIR_BASE . 'setting/');
if (!defined('NITROPACK_DIR_HTML')) define('NITROPACK_DIR_HTML', NITROPACK_DIR_BASE . 'html/');
if (!defined('NITROPACK_PARTNER_ID_FILE')) define('NITROPACK_PARTNER_ID_FILE', NITROPACK_DIR_SETTING . 'partner_id');
if (!defined('NITROPACK_OPENCART_VERSION')) define('NITROPACK_OPENCART_VERSION', VERSION);
if (!defined('NITROPACK_ERROR_LOG_FILESIZE_LIMIT')) define('NITROPACK_ERROR_LOG_FILESIZE_LIMIT', 8388608);
if (!defined('NITROPACK_DEBUG_LOG_FILESIZE_LIMIT')) define('NITROPACK_DEBUG_LOG_FILESIZE_LIMIT', 8388608);

// TRUE to disable NitroPack for all admin sessions. FALSE to enable it for everyone. This does not make NitroPack work on the admin panel.
if (!defined('NITROPACK_DISABLED_FOR_ADMIN')) define('NITROPACK_DISABLED_FOR_ADMIN', false);

// TRUE to always force cache invalidation in all cases, except for the Journal cookie events. FALSE to use the default behavior.
if (!defined('NITROPACK_AUTO_CACHE_CLEAR_ALWAYS_INVALIDATE')) define('NITROPACK_AUTO_CACHE_CLEAR_ALWAYS_INVALIDATE', false);

// TRUE to always force cache purge in all cases
if (!defined('NITROPACK_AUTO_CACHE_CLEAR_ALWAYS_PURGE')) define('NITROPACK_AUTO_CACHE_CLEAR_ALWAYS_PURGE', false);

// TRUE if quantity editions should clear all product pages. FALSE to clear only the product/product route for this product.
if (!defined('NITROPACK_AUTO_CACHE_CLEAR_QUANTITY_CLEAR_ALL')) define('NITROPACK_AUTO_CACHE_CLEAR_QUANTITY_CLEAR_ALL', true);

// TRUE if quantity comparison should be between absolute values, or FALSE to be between positive and non-positive values
if (!defined('NITROPACK_AUTO_CACHE_CLEAR_QUANTITY_COMPARISON_ABSOLUTE')) define('NITROPACK_AUTO_CACHE_CLEAR_QUANTITY_COMPARISON_ABSOLUTE', false);

// TRUE if stock status editions should clear all product pages. FALSE to clear only the product/product route for this product.
if (!defined('NITROPACK_AUTO_CACHE_CLEAR_STOCK_STATUS_CLEAR_ALL')) define('NITROPACK_AUTO_CACHE_CLEAR_STOCK_STATUS_CLEAR_ALL', true);

// TRUE if we want to mimic the OpenCart HTTP_ACCEPT_LANGUAGE logic in startup.php. FALSE for custom cases where this is disabled
if (!defined('NITROPACK_USE_HTTP_ACCEPT_LANGUAGE')) define('NITROPACK_USE_HTTP_ACCEPT_LANGUAGE', true);

// TRUE if we want to load elements asynchonously
if (!defined('NITROPACK_USE_ASYNC_ELEMENTS')) define('NITROPACK_USE_ASYNC_ELEMENTS', false);

// Select the storage engine, by default, it is DISK. Available options: DISK, REDIS
if (!defined('NITROPACK_STORAGE_DRIVER')) define('NITROPACK_STORAGE_DRIVER', 'DISK');

// Redis connection settings, used in case the NITROPACK_STORAGE_DRIVER is REDIS
if (!defined('NITROPACK_REDIS_HOSTNAME')) define('NITROPACK_REDIS_HOSTNAME', "127.0.0.1");
if (!defined('NITROPACK_REDIS_PORT')) define('NITROPACK_REDIS_PORT', 6379);
if (!defined('NITROPACK_REDIS_PASSWORD')) define('NITROPACK_REDIS_PASSWORD', NULL);
if (!defined('NITROPACK_REDIS_DATABASE')) define('NITROPACK_REDIS_DATABASE', NULL);

// TRUE to include ascending product category paths in the sitemap.
if (!defined('NITROPACK_SITEMAP_USE_ASC_PATHS')) define('NITROPACK_SITEMAP_USE_ASC_PATHS', false);

// TRUE to force use geoplugin.net
if (!defined('NITROPACK_FORCE_GEOIP')) define('NITROPACK_FORCE_GEOIP', false);

// TRUE to disable tagging of manufacturers. This would be helpful for cases where manufacturer tagging exists for most of the site pages. In such a case, store owners will have to do manual purge of their entire cache upon modifying any manufacturers. Default is FALSE
if (!defined('NITROPACK_DISABLE_MANUFACTURER_TAGGING')) define('NITROPACK_DISABLE_MANUFACTURER_TAGGING', false);

// In seconds. How long to wait between SDK health checks. Default is 2 minutes
if (!defined('NITROPACK_HEALTH_CHECK_INTERVAL')) define('NITROPACK_HEALTH_CHECK_INTERVAL', 120);

// In seconds. How long to wait to send health beacons. Default is 5 minutes
if (!defined('NITROPACK_HEALTH_BEACON_INTERVAL')) define('NITROPACK_HEALTH_BEACON_INTERVAL', 300);
