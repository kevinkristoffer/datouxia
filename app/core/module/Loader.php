<?php

class Puppy_Core_Module_Loader
{

    /**
     *
     * @var Puppy_Core_Module_Loader
     */
    private static $_instance;

    /**
     *
     * @var array
     */
    private $_moduleNames;

    /**
     *
     * @return Puppy_Core_Module_Loader
     */
    public static function getInstance()
    {
        if (null == self::$_instance) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        $this->_moduleNames = $this->_getModules();
    }

    public function getModuleNames()
    {
        return $this->_moduleNames;
    }

    public function getRoutes()
    {
        if (null == $this->_moduleNames) {
            return;
        }
        $router = new Zend_Controller_Router_Rewrite ();

        foreach ($this->_moduleNames as $moduleName) {
            $configFiles = $this->_loadRouteConfigs($moduleName);

            foreach ($configFiles as $file) {
                foreach ($file as $dir => $name) {
                    $config = new Zend_Config_Ini($dir . $name, 'routes');

                    $router->addConfig($config, 'routes');
                }
            }
        }

        return $router;
    }

    private function _getModules()
    {
        return Puppy_Core_Utility_File::getSubDir(APPLICATION_PATH . DS . 'modules');
    }

    private function _loadRouteConfigs($moduleName)
    {
        $dir = APPLICATION_PATH . DS . 'modules' . DS . $moduleName . DS . 'configs' . DS . 'routes' . DS;
        if (!is_dir($dir)) {
            return array();
        }

        $configFiles = array();

        $dirIterator = new DirectoryIterator ($dir);
        foreach ($dirIterator as $file) {
            if ($file->isDot() || $file->isDir()) {
                continue;
            }
            $name = $file->getFilename();
            if (preg_match('/^[^a-z]/i', $name) || ('CVS' == $name) || ('.svn' == strtolower($name)) ||
                ('.git' == strtolower($name))
            ) {
                continue;
            }
            $configFiles [] = array($dir => $name);
        }

        return $configFiles;
    }
}

?>