<?php
/*
 * define path seperator
 */
defined('PS') || define ('PS', PATH_SEPARATOR);
defined('DS') || define ('DS', DIRECTORY_SEPARATOR);
/*
 * define application root path
 */
defined('APP_ROOT') || define ('APP_ROOT', getcwd());
/*
 * define application path
 */
defined('APPLICATION_PATH') || define ('APPLICATION_PATH', APP_ROOT . DS . 'app');
/*
 * define application environment
 */
defined('APPLICATION_ENV') || define ('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
/*
 * include library path
 */
$path = array(realpath(APP_ROOT . DS . 'lib'));
$path [] = get_include_path();
set_include_path(implode(PS, $path));

/*
 * load global class
 */
require_once APPLICATION_PATH . DS . 'Puppy.php';

/*
 * load configuration and bootstrap
 */
require_once 'Zend/Application.php';

$configs = Puppy::getIniConfig('app');
$app = new Zend_Application (APPLICATION_ENV, $configs);

$app->bootstrap()->run();
