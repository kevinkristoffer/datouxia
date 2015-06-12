<?php

class Puppy_Core_Autoloader implements Zend_Loader_Autoloader_Interface
{
    public function autoload($class)
    {
        $paths = explode('_', $class);
        $file = substr($class, strlen('Puppy_'));
        $classFile = $paths[count($paths) - 1];
        $file = substr($file, 0, -strlen($classFile));
        $file = APPLICATION_PATH . DS . strtolower(str_replace('_', DS, $file)) . $classFile . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
}
