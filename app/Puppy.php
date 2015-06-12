<?php
require_once 'Zend/Registry.php';

class Puppy {

	const CLASS_PREFIX = 'Puppy';

	const WORD_DELIMITER = '_';

	const DEFAULT_CONFIG_KEY = 'app';

	const APPLICATION_CONFIG_DIR = 'configs';

	public static function loadClass($className)
	{
		$paths = explode ( self::WORD_DELIMITER, $className );
		$file = substr ( $className, strlen ( self::CLASS_PREFIX . self::WORD_DELIMITER ) );
		$classFile = $paths [count ( $paths ) - 1];
		$file = substr ( $file, 0, - strlen ( $classFile ) );
		$file = APPLICATION_PATH . DS . strtolower ( str_replace ( '_', DS, $file ) ) . $classFile . '.php';
		if (file_exists ( $file ))
		{
			require_once $file;
		} else
		{
			// throw exception
		}
	}

	public static function getIniConfig($key = null)
	{
		$key = ($key == null) ? self::DEFAULT_CONFIG_KEY : $key;
		
		if (! Zend_Registry::isRegistered ( $key ))
		{
			$configFileDirectory = APPLICATION_PATH . DS . self::APPLICATION_CONFIG_DIR . DS;
			self::loadClass ( 'Puppy_Core_Config' );
			$config = Puppy_Core_Config::loadIniConfig ( $configFileDirectory, $key . '.ini', APPLICATION_ENV );
			Zend_Registry::set ( $key, $config );
		}
		
		return Zend_Registry::get ( $key );
	}
}