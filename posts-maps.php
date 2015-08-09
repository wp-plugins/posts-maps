<?php
/**
 * @version 1.0.1
 */
/*
Plugin Name: Posts Maps
Description: Posts maps
Author: Dmitrij Mashkin
Version: 1.0
Author URI: http://mashkin.pro/
*/

$pluginRoot = dirname(__FILE__);
$pluginFolder = 'PostsMaps';

require_once 'sys' . DIRECTORY_SEPARATOR . 'core' .  DIRECTORY_SEPARATOR . 'Plugin.php';
require_once $pluginFolder . DIRECTORY_SEPARATOR . 'core' .  DIRECTORY_SEPARATOR . 'Plugin.php';
global $PostsMaps;
$PostsMaps = new PostsMapsCorePlugin($pluginRoot);
?>