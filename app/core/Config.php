<?php
require_once 'Zend/Loader.php';

class Puppy_Core_Config
{

    /**
     * Load Ini configuration
     *
     * @param string $directory
     * @param string $file
     * @return array
     */
    public static function loadIniConfig($directory, $file, $environment = null)
    {
        $cachedConfigFile = $directory . $file . '.php';
        $configIniFile = $directory . $file;
        if (!file_exists($cachedConfigFile) || filemtime($cachedConfigFile) < filemtime($configIniFile)) {
            Zend_Loader::loadClass('Zend_Config_Ini');
            $config = new Zend_Config_Ini ($configIniFile, $environment);
            file_put_contents($cachedConfigFile, '<?php ' . PHP_EOL . 'return ' . var_export($config->toArray(), true) .
                                                 ';');
        }
        $configArray = require($cachedConfigFile);
        return $configArray;
    }

    public static function loadXmlConfig($directory, $file)
    {
    }
}

?>